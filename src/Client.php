<?php

declare(strict_types=1);

namespace Kyto\Alibaba;

use Kyto\Alibaba\Exception\ResponseException;
use Kyto\Alibaba\Util\Clock;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @internal
 */
class Client
{
    public function __construct(
        private string $apiKey,
        private string $secret,
        private HttpClientInterface $httpClient,
        private Clock $clock,
    ) {
    }

    /**
     * Make request to Alibaba API
     *
     * @param mixed[] $payload JSON serializable data
     * @return mixed[] Decoded JSON as associative array
     */
    public function request(array $payload): array
    {
        $headers = ['User-Agent' => 'Kyto Alibaba Client'];
        $body = $this->getBody($payload);

        $response = $this->httpClient->request('POST', 'https://api.taobao.com/router/rest', [
            'headers' => $headers,
            'body' => $body,
        ]);

        $data = $response->toArray();
        $this->throwOnError($data);
        return $data;
    }

    /**
     * @param mixed[] $payload
     * @return mixed[]
     */
    private function getBody(array $payload): array
    {
        $payload = array_merge([
            'app_key' => $this->apiKey,
            'timestamp' => $this->getTimestamp(),
            'format' => 'json',
            'v' => '2.0',
        ], $payload);

        return $this->getSignedBody($payload);
    }

    /**
     * All API calls must include a valid signature. Requests with invalid signatures will be rejected.
     *
     * @param mixed[] $body
     * @return mixed[] Same body plus "sign_method" and "sign" values
     */
    private function getSignedBody(array $body): array
    {
        unset($body['sign']);
        $body['sign_method'] = 'md5';

        ksort($body);
        $hashString = '';
        foreach ($body as $key => $value) {
            $hashString .= $key . $value;
        }
        $hashString = $this->secret . $hashString . $this->secret;

        $body['sign'] = mb_strtoupper(md5($hashString));
        return $body;
    }

    /**
     * Required by Alibaba API specs to be in GMT+8 timezone
     */
    private function getTimestamp(): string
    {
        return $this->clock->now('GMT+8')->format('Y-m-d H:i:s');
    }

    /**
     * @param mixed[] $data
     */
    private function throwOnError(array $data): void
    {
        $errorResponse = $data['error_response'] ?? null;

        if ($errorResponse !== null) {
            throw new ResponseException(
                $errorResponse['msg'],
                (int) $errorResponse['code'],
                $errorResponse['sub_msg'],
                $errorResponse['sub_code'],
            );
        }
    }
}

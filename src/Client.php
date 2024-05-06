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
        private string $key,
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
    public function request(string $endpoint, array $payload): array
    {
        $endpoint = str_starts_with($endpoint, '/') ? $endpoint : '/' . $endpoint;
        $headers = ['User-Agent' => 'Kyto Alibaba Client'];
        $body = $this->getBody($endpoint, $payload);

        $response = $this->httpClient->request('POST', 'https://openapi-api.alibaba.com/rest' . $endpoint, [
            'headers' => $headers,
            'body' => $body,
        ]);

        $data = $response->toArray();
        $this->throwOnError($endpoint, $data);
        return $data;
    }

    /**
     * @param mixed[] $payload
     * @return mixed[]
     */
    private function getBody(string $endpoint, array $payload): array
    {
        $payload = array_merge([
            'app_key' => $this->key,
            'timestamp' => $this->getTimestamp(),
        ], $payload);

        return $this->getSignedBody($endpoint, $payload);
    }

    /**
     * All API calls must include a valid signature. Requests with invalid signatures will be rejected.
     * @link https://openapi.alibaba.com/doc/doc.htm?docId=19#/?docId=60
     * @link https://openapi.alibaba.com/doc/doc.htm?docId=19#/?docId=58
     *
     * @param mixed[] $body
     * @return mixed[] Same body plus "sign_method" and "sign" values
     */
    private function getSignedBody(string $endpoint, array $body): array
    {
        unset($body['sign']);
        $body['sign_method'] = 'sha256';
        ksort($body);

        $hashString = $endpoint;
        foreach ($body as $key => $value) {
            $hashString .= $key . $value;
        }

        $body['sign'] = strtoupper(hash_hmac('sha256', $hashString, $this->secret));
        return $body;
    }

    /**
     * Required by Alibaba to be in microseconds and (seems like) in UTC timezone.
     */
    private function getTimestamp(): string
    {
        return $this->clock->now('UTC')->format('Uv');
    }

    /**
     * @param mixed[] $data
     */
    private function throwOnError(string $endpoint, array $data): void
    {
        if (isset($data['type'], $data['code'])) {
            throw new ResponseException(
                $endpoint,
                $data['type'],
                $data['message'],
                $data['code'],
                $data['request_id'],
                $data['_trace_id_'],
            );
        }

        if (isset($data['result']['success']) && (bool) $data['result']['success'] !== true) {
            throw new ResponseException(
                $endpoint,
                'SYSTEM', // it's empty for this type of error, therefore we use "SYSTEM"
                $data['result']['message_info'],
                $data['result']['msg_code'],
                $data['request_id'],
                $data['_trace_id_'],
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Enum;

enum InputType: string
{
    case INPUT = 'input';
    case MULTI_SELECT = 'multi_select';
    case SINGLE_SELECT = 'single_select';
}

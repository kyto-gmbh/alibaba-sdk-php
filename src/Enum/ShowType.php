<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Enum;

enum ShowType: string
{
    case CHECK_BOX = 'check_box'; // multi_select
    case GROUP_TABLE = 'group_table'; // single_select
    case INPUT = 'input'; // input (string)
    case INTERVAL = 'interval'; // input (number)
    case LIST_BOX = 'list_box'; // single_select
    case MODEL_NUMBERS = 'model_numbers'; // input (string)
}

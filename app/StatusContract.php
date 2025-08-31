<?php

namespace App;

enum StatusContract: int
{
    case PROFORMA = 1;
    case PROFORMA_FAIL = 2;
    case CONTRACT = 3;
    case CONTRACT_FAIL = 4;
    case CONTRACT_COMPLETE = 5;
}

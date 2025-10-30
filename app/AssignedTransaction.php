<?php

namespace App;

enum AssignedTransaction: int
{
    case UNASSIGNED = 0;
    case BILL = 1;
    case OPERATING = 2;
    case COMISION = 3;
    case BANK = 4;
    case INTEREST = 5;
    case UNEXPECTED = 6;
    case PURCHASE_TOTAL = 7;
    case UTILITY = 8;
}

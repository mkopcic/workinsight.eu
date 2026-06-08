<?php

namespace App\Domain\Shared\Enums;

enum MealSlot: string
{
    case Soup = 'soup';
    case Main = 'main';
    case Side = 'side';
    case Dessert = 'dessert';
}

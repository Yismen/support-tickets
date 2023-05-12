<?php

namespace Dainsys\Support\Enums;

enum ColorsEnum : string
{   case BLUE_900 = '#0D47A1';
    case GREEN_900 = '#1B5E20';
    case GREY_900 = '#212121';
    case ORANGE_900 = '#E65100';
    case PURPLE_900 = '#4A148C';
    case RED_900 = '#B71C1C';
    case BLUE_700 = '#1976D2';
    case GREEN_700 = '#388E3C';
    case GREY_700 = '#616161';
    case ORANGE_700 = '#F57C00';
    case PURPLE_700 = '#7B1FA2';
    case RED_700 = '#D32F2F';
    case BLUE_200 = '#90CAF9';
    case GREEN_200 = '#A5D6A7';
    case GREY_200 = '#EEEEEE';
    case ORANGE_200 = '#FFCC80';
    case PURPLE_200 = '#CE93D8';
    case RED_200 = '#EF9A9A';
    case BLUE_300 = '#64B5F6';
    case GREEN_300 = '#81C784';
    case GREY_300 = '#E0E0E0';
    case ORANGE_300 = '#FFB74D';
    case PURPLE_300 = '#BA68C8';
    case RED_300 = '#E57373';
    case BLUE_400 = '#42A5F5';
    case GREEN_400 = '#66BB6A';
    case GREY_400 = '#BDBDBD';
    case ORANGE_400 = '#FFA726';
    case PURPLE_400 = '#AB47BC';
    case RED_400 = '#EF5350';
    case BLUE_500 = '#2196F3';
    case GREEN_500 = '#4CAF50';
    case GREY_500 = '#9E9E9E';
    case ORANGE_500 = '#FF9800';
    case PURPLE_500 = '#9C27B0';
    case RED_500 = '#F44336';
    case BLUE_600 = '#1E88E5';
    case GREEN_600 = '#43A047';
    case GREY_600 = '#757575';
    case ORANGE_600 = '#FB8C00';
    case PURPLE_600 = '#8E24AA';
    case RED_600 = '#E53935';
    case WHITE = '#FFFFFF';

    public static function colorIndex(int $index): self
    {
        $cases = self::cases();

        return $cases[$index] ?? self::random();
    }

    public static function random(): self
    {
        $cases = self::cases();

        return $cases[array_rand($cases)];
    }

    public static function contextColor(float $goal, float $result): string
    {
        $percentage = $goal > 0 ? $result / $goal : 0;

        if ($result >= .9) {
            return self::GREEN_500->value;
        }

        if ($percentage >= .8) {
            return self::ORANGE_500->value;
        }

        return self::RED_500->value;
    }
}

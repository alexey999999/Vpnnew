<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Throwable;

class Helper
{
    public static function getExceptionData(Throwable $exception, bool $withTrace = false, bool $traceAsString = false): array
    {
        $data = [
            'title' => 'Ошибка',
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        if ($withTrace) {
            $data['trace'] = $traceAsString ? $exception->getTraceAsString() : $exception->getTrace();
        }

        return $data;
    }

    public static function printErrorToLog(Throwable $exception): void
    {
        Log::error(
            print_r(
                self::getExceptionData($exception, true, true),
                true
            )
        );
    }

    public static function errorMsg(Throwable $exception): string
    {
        return sprintf('%s. %s:%s', $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }

    public static function humanDate($date, $format = 'D MMM H:mm'): string
    {
        return Carbon::createFromDate($date)->setTimezone(new DateTimeZone('Europe/Moscow'))->locale('ru')->isoFormat($format);
    }

    public static function remainingTo($date): string
    {
        $now = Carbon::now();

        return Carbon::createFromDate($date)->diff($now)->format('%h ч %I мин');
    }

    public static function diffDate($date)
    {
        $now = Carbon::now();

        return Carbon::createFromDate($date)->diff($now);
    }

    public static function paginate($items, $perPage = 5, $page = null, $options = [], $path = null): LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $paginator = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        $paginator?->setPath($path);

        return $paginator;
    }

    public static function pluralize(int $count, $titles): string
    {
        $cases = [2, 0, 1, 1, 1, 2];

        return $titles[($count % 100 > 4 && $count % 100 < 20) ? 2 : $cases[min($count % 10, 5)]];
    }

    public static function checkRemoteFileExists(string $url): string|bool
    {
        $headers = get_headers($url, true);
        $response = ($headers && isset($headers[0])) ? $headers[0] : null;

        return (! str_contains($response, '200')) ? false : $url;
    }

    public static function dateDiffDays($date): int
    {
        return Carbon::createFromDate($date)->diffInDays(Carbon::now());
    }

    public static function dateDiffDaysWithLabel($date): string
    {
        $variants = [
            'день',
            'дня',
            'дней',
        ];

        $days = self::dateDiffDays($date);

        return sprintf('%s %s', $days, self::pluralize($days, $variants));
    }
}

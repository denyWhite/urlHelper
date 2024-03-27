<?php
declare(strict_types=1);

namespace App\Tests\TestCase;

use App\Url;
use App\WrongUrlException;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    /**
     * @return iterable<array<string>>
     */
    public function urlsDataProvider(): iterable
    {
        yield 'case 1' => ['http://user:password@yandex.ru:3/path/is/the/path?a=1#foo'];
        yield 'case 2' => ['http://user@yandex.ru:3/path/is/the/path?a=1#foo'];
        yield 'case 3' => ['https://user@yandex.ru:3/path/is/the/path?a%5B0%5D=1&a%5B1%5D=2'];
    }

    /**
     * @param string $url
     * @return void
     * @throws WrongUrlException
     * @dataProvider urlsDataProvider()
     */
    public function testFactory(string $url): void
    {
        self::assertSame($url, (string)(new Url($url)));
    }

    /**
     * @throws WrongUrlException
     */
    public function testBuilder(): void
    {
        self::assertEquals(
            'https://ya.ru/path/second?a=1&b=2&c%5B0%5D=3&c%5B1%5D=4&c%5B2%5D=5',
            (new Url())->withHttpsScheme()
                ->setHost('ya.ru')
                ->setPathParts(['path', 'second'])
                ->setQueryParams([
                    'a' => 1,
                    'b' => 2,
                    'c' => [
                        3,
                        4,
                        5,
                    ]
                ])
                ->toString()
        );

    }
}
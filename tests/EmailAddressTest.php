<?php
namespace Vanio\EasyMailer\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Vanio\EasyMailer\EmailAddress;

class EmailAddressTest extends TestCase
{
    function test_email_address_constructor_sets_email_correctly()
    {
        $this->assertSame('john@doe.com', (new EmailAddress('john@doe.com', 'John Doe'))->email());
    }

    function test_email_address_constructor_sets_name_correctly()
    {
        $this->assertSame('John Doe', (new EmailAddress('john@doe.com', 'John Doe'))->name());
    }

    function test_to_string_conversion()
    {
        $this->assertSame('John Doe <john@doe.com>', (string) new EmailAddress('john@doe.com', 'John Doe'));
        $this->assertSame('john@doe.com', (string) new EmailAddress('john@doe.com'));
    }

    function test_create_new_email_address_from_string()
    {
        $this->assertSame('john@doe.com', EmailAddress::fromString('john@doe.com')->email());
        $this->assertSame('john@doe.com', EmailAddress::fromString('John Doe <john@doe.com>')->email());
        $this->assertSame('John Doe', EmailAddress::fromString('John Doe <john@doe.com>')->name());
    }

    function test_invalid_email_address_string_causes_exception_throw()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"Blah <Bogus><Foo>" is not a valid e-mail address.');

        EmailAddress::fromString('Blah <Bogus><Foo>');
    }

    function test_malformed_email_causes_exception_throw()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"john@doe@com" is not a valid e-mail address.');

        new EmailAddress('john@doe@com');
    }
}

<?php
namespace Vanio\EasyMailer\Tests;

use PHPUnit_Framework_TestCase;
use Vanio\EasyMailer\HtmlMessageContent;

class HtmlMessageContentTest extends PHPUnit_Framework_TestCase
{
    function test_message_has_correct_mime_type()
    {
        $this->assertSame('text/html', (new HtmlMessageContent(''))->mimeType());
    }

    function test_message_can_be_converted_to_plain_text()
    {
        $this->assertSame('Test', (new HtmlMessageContent('', 'Test'))->asPlainText());
        $this->assertSame(
            "Hello, world!\n\nThis is a test message.",
            (new HtmlMessageContent('<p>Hello, world!</p><p>This is a test message.</p>'))->asPlainText()
        );
    }

    function test_message_to_string_conversion()
    {
        $this->assertSame('<p>Hello, world!</p>', (string) new HtmlMessageContent('<p>Hello, world!</p>'));
    }

    function test_message_html_emogrifying()
    {
        $html = '<style>* { color: red; } p { color: blue; }</style><div><p>Test.</p></div>';
        $emogrified = (new HtmlMessageContent($html))->emogrify();

        $this->assertContains('<div style="color: red;"><p style="color: blue;">Test.</p></div>', $emogrified);
    }
}

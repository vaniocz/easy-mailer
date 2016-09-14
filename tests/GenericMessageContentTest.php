<?php
namespace Vanio\EasyMailer\Tests;

use PHPunit\Framework\TestCase;
use Vanio\EasyMailer\GenericMessageContent;

class GenericMessageContentTest extends TestCase
{
    function test_mime_type_setting()
    {
        $this->assertSame('text\plain', (new GenericMessageContent('text\plain', ''))->mimeType());
    }

    function test_content_setting()
    {
        $this->assertSame('Test Content', (string) (new GenericMessageContent('text\plain', 'Test Content')));
    }

    function test_plain_text_setting()
    {
        $this->assertSame('Test', (new GenericMessageContent('text\xml', '<p>Test</p>', 'Test'))->asPlainText());
    }
}

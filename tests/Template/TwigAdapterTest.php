<?php
namespace Vanio\EasyMailer\Tests\Template;

use PHPUnit\Framework\TestCase;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Vanio\EasyMailer\EmailAddress;
use Vanio\EasyMailer\Message;
use Vanio\EasyMailer\Template\TwigAdapter;

class TwigAdapterTest extends TestCase
{
    /** @var Twig_Environment */
    private $twig;

    /** @var Message */
    private $message;

    protected function setUp()
    {
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem(__DIR__ . '/../Fixtures/templates/twig'));
        $this->message = (new TwigAdapter($this->twig))->createMessage('testMessage.html.twig', ['lang' => 'en']);
    }

    function test_message_has_correct_title()
    {
        $this->assertSame('Some cool title!', $this->message->title());
    }

    function test_message_has_correct_subject()
    {
        $this->assertSame('Test message', $this->message->subject());
    }

    function test_message_has_correct_recipients()
    {
        $this->assertEquals([
            new EmailAddress('john@doe.com', 'John Doe'),
            new EmailAddress('homer.simpson@fox.com'),
        ], $this->message->to());
    }

    function test_message_has_correct_cc()
    {
        $this->assertEquals([
            new EmailAddress('jane@doe.com', 'Jane Doe'),
            new EmailAddress('jane.doe@foo.bar'),
        ], $this->message->cc());
    }

    function test_message_has_correct_bcc()
    {
        $this->assertEquals([
            new EmailAddress('homer.simpson@fox.com'),
        ], $this->message->bcc());
    }

    function test_message_has_correct_sender()
    {
        $this->assertEquals(new EmailAddress('info@vanio.cz', 'Vanio EasyMailer'), $this->message->sender());
    }

    function test_message_has_correct_from()
    {
        $this->assertEquals([
            new EmailAddress('info@vanio.cz', 'Vanio EasyMailer'),
            new EmailAddress('foo@bar.baz'),
        ], $this->message->from());
    }

    function test_html_content_has_correct_mime_type()
    {
        $this->assertSame('text/html', $this->message->content()->mimeType());
    }

    function test_html_content_is_correct()
    {
        $this->assertContains('<title>Some cool title!</title>', (string) $this->message->content());
        $this->assertContains(
            '<body>Dear Jane and John, I wish I had something to say. Best regards.</body>',
            (string) $this->message->content()
        );
    }

    function test_alternative_text_content_is_correct()
    {
        $this->assertSame(
            "C'mon, don't tell me your client can't display HTML!",
            $this->message->content()->asPlainText()
        );
    }

    function test_non_html_content_has_correct_mime_type()
    {
        $message = (new TwigAdapter($this->twig))->createMessage('nonHtmlTestMessage.txt.twig', []);

        $this->assertSame('text/plain', $message->content()->mimeType());
    }

    function test_context_passing()
    {
        $message = (new TwigAdapter($this->twig))->createMessage('testMessage.html.twig', ['lang' => 'cs']);

        $this->assertContains('<html lang="cs">', (string) $message->content());
    }

    function test_file_can_be_embedded()
    {
        $this->assertSame('/some/image.png', current($this->message->content()->embeddedAttachments()));
    }

    function test_files_can_be_attached()
    {
        $this->assertSame(
            ['/some/file1.txt', '/some/file2.txt'],
            array_values($this->message->content()->attachments())
        );
    }

    function test_emogrifying_message()
    {
        $message = (new TwigAdapter($this->twig))->createMessage('emogrifiedTestMessage.html.twig', []);
        $this->assertContains('<p style="color: blue;">Test.</p>', (string) $message->content());
    }

    function test_twig_runtime_exceptions_are_not_swallowed()
    {
        $this->expectException(\Twig_Error_Runtime::class);
        (new TwigAdapter($this->twig))->createMessage('invalidMessage.twig', []);
    }
}

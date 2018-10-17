<?php
namespace Vanio\EasyMailer\Tests;

use PHPUnit\Framework\TestCase;
use Vanio\EasyMailer\EasyMailer;
use Vanio\EasyMailer\Mailer\MailerAdapter;
use Vanio\EasyMailer\Message;
use Vanio\EasyMailer\Template\TemplateEngineAdapter;

class EasyMailerTest extends TestCase
{
    /** @var TemplateEngineAdapter */
    private $templateEngineAdapter;

    /** @var MailerAdapter */
    private $mailerAdapter;

    /** @var EasyMailer */
    private $easyMailer;

    protected function setUp()
    {
        $this->templateEngineAdapter = $this->getMockBuilder(TemplateEngineAdapter::class)->getMock();
        $this->mailerAdapter = $this->getMockBuilder(MailerAdapter::class)->getMock();
        $this->easyMailer = new EasyMailer($this->templateEngineAdapter, $this->mailerAdapter);
    }

    function test_message_sending()
    {
        $message = $this->getMockBuilder(Message::class)->disableOriginalConstructor()->getMock();
        $to = ['John Doe <to@foo.bar>'];
        $cc = ['cc@foo.bar'];
        $bcc = ['bcc@foo.bar'];

        $this->templateEngineAdapter
            ->expects($this->once())
            ->method('createMessage')
            ->with('some_dir/some_template.html.twig', ['test' => 'Test'])
            ->will($this->returnValue($message));

        $this->mailerAdapter
            ->expects($this->once())
            ->method('sendMessage')
            ->with($message, $to, $cc, $bcc);

        $this->easyMailer->send('some_dir/some_template.html.twig', ['test' => 'Test'], [$to[0]], [$cc[0]], [$bcc[0]]);
    }

    function test_disabling_delivery()
    {
        $this->mailerAdapter
            ->expects($this->never())
            ->method('sendMessage');

        $this->easyMailer->disableDelivery();
        $this->easyMailer->send('some_dir/some_template.html.twig', [], ['John Doe <to@foo.bar>']);
    }

    function test_enabling_delivery()
    {
        $this->mailerAdapter
            ->expects($this->once())
            ->method('sendMessage');

        $this->easyMailer->disableDelivery();
        $this->easyMailer->enableDelivery();
        $this->easyMailer->send('some_dir/some_template.html.twig', [], ['John Doe <to@foo.bar>']);
    }
}

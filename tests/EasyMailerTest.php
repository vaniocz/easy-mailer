<?php
namespace Vanio\EasyMailer\Tests;

use PHPUnit\Framework\TestCase;
use Vanio\EasyMailer\EasyMailer;
use Vanio\EasyMailer\EmailAddress;
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

        $this->easyMailer->send(
            'some_dir/some_template.html.twig',
            ['test' => 'Test'],
            [EmailAddress::fromString($to[0])],
            [EmailAddress::fromString($cc[0])],
            [EmailAddress::fromString($bcc[0])]
        );
    }
}

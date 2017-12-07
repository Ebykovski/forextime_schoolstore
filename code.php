<?php

namespace Notifier;

use \Todo\TodoService;
use \Todo\TodoServiceClient;

/**
 * Class for notify users by add comments for post task
 */
class TodoNotifier implements NotifierInterface
{
    public $taskId = null;

    public $comment = null;

    public $tmpFiles = array();

    /**
     * do not use globals in classes!!
     *
     * @global string $smtp_host
     * @param string $subject
     * @param string $body
     * @param array $addresses
     * @return boolean
     */
    public static function sendEmail($subject, $body, $addresses)
    {
        global $smtp_host;

        $mailer = new phpmailer();
        $mailer->IsSMTP();
        $mailer->IsHTML(true);
        $mailer->CharSet = 'utf-8';
        $mailer->Host = $smtp_host;
        $mailer->Subject = $subject ? : 'Email notification';
        $mailer->Body = $body;

        foreach ($addresses as $address)
            $mailer->AddAddress($address, '', 0);

        return $mailer->Send();
    }

    /**
     * if Configuration is singletone
     * why use array acces interface?
     *
     * @return type
     */
    private function getPostTask()
    {
        $cfg = \Configuration::getInstance();
        $host = $cfg['todo']['host'];
        $user = $cfg['todo']['user'];

        $todoService = new TodoService(new TodoServiceClient($host, $user));
        return $todoService->getTask($this->taskId);
    }

    /**
     *
     * @return type
     */
    private function getPostComment()
    {
        if (!$this->comment)
            $this->comment = $this->getPostTask()->addComment('');

        return $this->comment;
    }

    /**
     * Need add backslash before "Exception" or add it in "use" section
     * or maybe you really have "\Notifier\Exception" ?
     *
     * @param string $fileLabel
     * @param sring $string
     * @return TodoNotifier
     * @throws Exception
     */
    public function attachString($fileLabel, $string)
    {
        $fileLabel = str_replace('..', '', $fileLabel);
        $tmpFilePath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$fileLabel;
        if (!touch($tmpFilePath))
            throw new Exception('Unable to touch target file');

        file_put_contents($tmpFilePath, $string);

        $this->getPostComment()->attachFile($tmpFilePath);

        $this->tmpFiles[] = $tmpFilePath;

        return $this;
    }

    /**
     *
     * @param string $file
     * @return TodoNotifier
     */
    public function attachFile($file)
    {
        $this->getPostComment()->attachFile($file);

        return $this;
    }

    /**
     * Vary bad variable names!
     *
     * "mysql_*" old and deprecated in php 7.0 use mysqli_*
     * "mysql_fetch_result" function not exists
     * this need "mysql_fetch_row"
     *
     * $email not escaped! potential sql injection
     *
     * "crc32" return integer, pls use functions for create hash like "md5"
     * 3 letters (digits for htis) very small
     * potential you have doubles
     *
     * @param string $email
     * @return string
     */
    private function createAuthentication($email)
    {
        $conn = mysql_connect();
        $aaaaa = mysql_query("select * from user_salts where email=$email");
        $x = mysql_fetch_result($aaaaa);
        return substr(crc32($x['salt'].$email), 0, 3);
    }

    /**
     * "strpos" return start position of substring
     * and "if" represent it as "false"
     * this, if $authorEmail start from "test", send message
     * need check false === strpos($authorEmail, "test")
     *
     * ForEach Loops Must Use Braces
     * If-Else Statements Must Use Braces
     *
     * @param string $authorEmail
     * @param string $message
     * @return boolean
     */
    public function notify($authorEmail, $message = null)
    {
        if ($message !== null)
            $this->setMessage($message);

        // Treat ALL emails containing "test" as test authors and never send from them
        if (strpos($authorEmail, "test")) {
            return true;
        }

        $auth = $this->createAuthentication($authorEmail);
        $result = $this->getPostComment()->save($authorEmail, $auth);

        foreach ($this->tmpFiles as $f)
            if (file_exists($f))
                unlink($f);

        return $result;
    }

    /**
     *
     * @param string $message
     * @return TodoNotifier
     */
    public function setMessage($message)
    {
        $this->getPostComment()->setBody($message);

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->getPostComment()->getBody();
    }

    /**
     * Set taskId
     *
     * @param integer $taskId
     * @return TodoNotifier
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * Get taskId
     *
     * @return integer
     */
    public function getTaskId()
    {
        return $this->taskId;
    }
}

<?php

/**
 * Sends and receives messages using System V message queues.
 */
class NfySysVQueue extends NfyQueue
{
	const MSG_MAXSIZE = 1024;
	/**
	 * @var integer Number representing the current queue, obtained by ftok(), used by msg_* functions family.
	 */
	private $_key;
	/**
	 * @var resource A handle obtained using msg_get_queue().
	 */
	private $_queue;
	/**
	 * @var integer New queues filesystem permissions, defaults to 0666, @see msg_get_queue().
	 */
	public $permissions = 0666;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		if (strlen($this->id)!==1) {
			throw new CException(Yii::t('NfyModule.app', 'Queue id must be exactly a one character.'));
		}
	}

	/**
	 * Return a number representing the current queue.
	 * @return integer
	 */
	private function getKey()
	{
		if ($this->_key === null) {
			$this->_key = ftok(__FILE__, $this->id);
		}
		return $this->_key;
	}
	private function getQueue()
	{
		if ($this->_queue === null) {
			$this->_queue = msg_get_queue($this->getKey(), $this->permissions);
		}
		return $this->_queue;
	}
	/**
	 * Creates an instance of NfyMessage model. The passed message body may be modified, @see formatMessage().
	 * This method may be overriden in extending classes.
	 * @param string $body message body
	 * @return NfyMessage
	 */
	protected function createMessage($body)
	{
		$now = new DateTime('now', new DateTimezone('UTC'));
		$message = new NfyMessage;
		$message->setAttributes(array(
			'created_on'	=> $now->format('Y-m-d H:i:s'),
			'sender_id'		=> Yii::app()->hasComponent('user') ? Yii::app()->user->getId() : null,
			'body'			=> $body,
		));
		return $this->formatMessage($message);
	}

	/**
	 * Formats the body of a queue message. This method may be overriden in extending classes.
	 * @param NfyMessage $message
	 * @return NfyMessage $message
	 */
	protected function formatMessage($message)
	{
		return $message;
	}

	/**
	 * @inheritdoc
	 */
	public function send($message, $category=null) {
		$queueMessage = $this->createMessage($message);

        if ($this->beforeSend($queueMessage) !== true) {
			Yii::log(Yii::t('NfyModule.app', "Not sending message '{msg}' to queue {queue_label}.", array('{msg}' => $queueMessage->body, '{queue_label}' => $this->label)), CLogger::LEVEL_INFO, 'nfy');
            return;
        }

		$success = msg_send($this->getQueue(), 1, $queueMessage, true, false, $errorcode);
        if (!$success) {
			Yii::log(Yii::t('NfyModule.app', "Failed to save message '{msg}' in queue {queue_label}.", array('{msg}' => $queueMessage->body, '{queue_label}' => $this->label)), CLogger::LEVEL_ERROR, 'nfy');
			if ($errorcode === MSG_EAGAIN) {
				Yii::log(Yii::t('NfyModule.app', "Queue {queue_label} is full.", array('{queue_label}' => $this->label)), CLogger::LEVEL_ERROR, 'nfy');
			}
            return false;
        }

        $this->afterSend($queueMessage);

		Yii::log(Yii::t('NfyModule.app', "Sent message '{msg}' to queue {queue_label}.", array('{msg}' => $queueMessage->body, '{queue_label}' => $this->label)), CLogger::LEVEL_INFO, 'nfy');
	}

	/**
	 * @inheritdoc
	 */
	public function peek($subscriber_id=null, $limit=-1, $status=NfyMessage::AVAILABLE)
	{
		throw new CException('Not implemented. System V queues does not support peeking. Use the receive() method.');
	}

	/**
	 * @inheritdoc
	 */
	public function reserve($subscriber_id=null, $limit=-1)
	{
		throw new CException('Not implemented. System V queues does not support reserving messages. Use the receive() method.');
	}

	/**
	 * Gets available messages from the queue and removes them from the queue.
	 * @param mixed $subscriber_id unused, must be null
	 * @param integer $limit number of available messages that will be fetched from the queue, defaults to -1 which means no limit
	 * @return array of NfyMessage objects
	 */
	public function receive($subscriber_id=null, $limit=-1)
	{
		if ($subscriber_id !== null) {
			throw new CException('Not implemented. System V queues does not support subscriptions.');
		}
		$flags = $this->blocking ? 0 : MSG_IPC_NOWAIT;
		$messages = array();
		$count = 0;
		while (($limit == -1 || $count < $limit) && (msg_receive($this->getQueue(), 0, $msgtype, self::MSG_MAXSIZE, $message, true, $flags, $errorcode))) {
			$message->subscriber_id = $subscriber_id;
			$message->status = NfyMessage::AVAILABLE;
			$messages[] = $message;
			$count++;
		}

		return $messages;
	}

	/**
	 * @inheritdoc
	 */
	public function delete($message_id, $subscriber_id=null)
	{
		throw new CException('Not implemented. System V queues does not support reserving messages.');
	}

	/**
	 * @inheritdoc
	 */
	public function release($message_id, $subscriber_id=null)
	{
		throw new CException('Not implemented. System V queues does not support reserving messages.');
	}

	/**
	 * @inheritdoc
	 */
	public function releaseTimedout()
	{
		throw new CException('Not implemented. System V queues does not support reserving messages.');
	}

	/**
	 * @inheritdoc
	 */
	public function subscribe($subscriber_id, $label=null, $categories=null, $exceptions=null)
	{
		throw new CException('Not implemented. System V queues does not support subscriptions.');
	}

	/**
	 * @inheritdoc
	 */
	public function unsubscribe($subscriber_id, $permanent=true)
	{
		throw new CException('Not implemented. System V queues does not support subscriptions.');
	}

	/**
	 * @inheritdoc
	 */
	public function isSubscribed($subscriber_id)
	{
		throw new CException('Not implemented. System V queues does not support subscriptions.');
	}

	/**
	 * @inheritdoc
	 */
	public function getSubscriptions($subscriber_id=null)
	{
		throw new CException('Not implemented. System V queues does not support subscriptions.');
	}
}

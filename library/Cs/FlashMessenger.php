<?php
/**
 * A stategy to Zend_Controlller_Action_Helper_FlashMessenger
 *
 * @author Cesar Scur
 */
class Cs_FlashMessenger {

    /**
     * @const SUCCESS
     */
    const SUCCESS = 'sucecss';

    /**
     * @const WARNING
     */
    const WARNING = 'warning';

    /**
     * @const NOTICE
     */
    const NOTICE = 'notice';

    /**
     * @const ERROR
     */
    const ERROR = 'error';


    /**
     * Output Wrapper
     * @var string
     */
    static protected $_wrapper = '<ul>%s</ul>';

    /**
     * Output line template:
     * 1st arg is the key of a indentified array
     * 2nd agr is the message it self
     * @var string
     *
     */
    static protected $_template = '<li class="%s">%s</li>';

    /**
     * Default key seted on 1st arg of template when message is not a array
     * @var string
     */
    static protected $_defaultKey = 'success';

    /**
     * FlashMessenger
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    static protected $_flashMessenger;


    /**
     * Call addMessage if a message is set
     * @param mixed $message
     */
    public function  __construct($message = null)
    {
        if( $message ) {
            self::addMessage($message);
        }
    }

    /**
     * Call Zend flashMessenger addMesage
     * @param mixed $message
     */
    static function addMessage($message, $messageKey = null)
    {
        if (null == $messageKey ) {
            $messageKey = self::$_defaultKey;
        }
        $message = array(
            $messageKey => $message
        );

        $flashMessenger = self::_getFlashMessenger();
        return $flashMessenger->addMessage($message);
    }

    /**
     * Clear current messages
     */
    static function clearCurrentMessages()
    {
        $flashMessenger = self::_getFlashMessenger();
        $flashMessenger->clearCurrentMessages();
    }


    /**
     * Display Flash Messages.
     *
     * @param  string $key Level of message that will be displaied
     * @return string Flash messages formatted for output
     */
    public function render()
    {
        $flashMessenger = $this->_getFlashMessenger();

        //get messages from previous requests
        $messages = $flashMessenger->getMessages();

        //add any messages from this request
        if ($flashMessenger->hasCurrentMessages()) {
            $messages = array_merge(
                $messages,
                $flashMessenger->getCurrentMessages()
            );
            //we don't need to display them twice.
            $flashMessenger->clearCurrentMessages();
        }


        $output ='';
        foreach ($messages as $message)
        {
            if (is_array($message)) {
                list($messageKey,$message) = each($message);
            } else {
                $messageKey = self::$_defaultKey;
            }

            $output .= sprintf(self::$_template, $messageKey, $message);
        }

        if ( $output ) {
            $output = sprintf(self::$_wrapper, $output);
        }

        return $output;
    }

    /**
     * FlashMessenger Instance.
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    protected static function _getFlashMessenger()
    {
        if( null === self::$_flashMessenger ) {
            self::$_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        }
        return self::$_flashMessenger;
    }

    /**
     * Magic to string
     * @return string
     */
    public function  __toString() {
        return $this->render();
    }

}



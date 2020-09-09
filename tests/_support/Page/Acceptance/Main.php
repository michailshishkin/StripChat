<?php

namespace Page\Acceptance;

class Main
{
    // include url of current page
    public static $URL = 'trysql.asp?filename=trysql_select_all';
    public static $runButton = '/html/body/div[2]/div/div[1]/div[1]/button';
    public static $resultSQL = '//*[@id="divResultSQL"]/div';
    public static $restoreDB = '//*[@id="restoreDBBtn"]';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL . $param;
    }

    /**
     * @var \AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    public function prepare()
    {
        $I = $this->acceptanceTester;
        $I->amOnPage(self::$URL);
    }
}

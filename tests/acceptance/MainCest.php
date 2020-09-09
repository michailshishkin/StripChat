<?php

use Page\Acceptance\Main;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertTrue;

class MainCest
{
    // tests
    public function firstTest(AcceptanceTester $I, Main $mainPage)
    {
        $mainPage->prepare();

        $searchRow = 'Giovanni Rovelli Via Ludovico il Moro 22';
        $sql = 'SELECT * FROM Customers';
        $txtArea = $I->executeJS('return window.editor.getValue();');
        if (strpos($txtArea, $sql) !== false) {
            $I->click($mainPage::$runButton);
        } else {
            $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql . '");');
            $I->click($mainPage::$runButton);
        }

        $I->waitForElementVisible('div#divResultSQL table', 1);
        $tableValue = $I->grabMultiple($mainPage::$table);
        unset($tableValue[0]);
        $result = false;

        foreach ($tableValue as $row) {
            if (strpos($row, $searchRow) !== false) {
                $result = true;
            };
        }
        assertTrue($result, 'There are no entries in the table that match = ' . $searchRow . '');
    }

    public function secondTest(AcceptanceTester $I, Main $mainPage)
    {
        $mainPage->prepare();

        $sql = "SELECT * FROM Customers C WHERE C.City = 'London'";
        $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql . '");');
        $I->click($mainPage::$runButton);
        $tableValue = $I->grabMultiple($mainPage::$table);
        unset($tableValue[0]);
        assertCount(6, array_count_values($tableValue), 'The number of rows in the table does not match the expected');
    }

    public function thirdTest(AcceptanceTester $I, Main $mainPage)
    {
        $mainPage->prepare();

        $searchRow = 'WhiteBear Mikhail Shishkin';
        $sql = "INSERT INTO Customers (CustomerName, ContactName, Address, City, PostalCode, Country) VALUES ('WhiteBear', 'Mikhail Shishkin', '2nd Mirgorodskaya, 9', 'Novosibirsk','630058', 'Russian Federation')";
        $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql . '");');
        $I->click($mainPage::$runButton);
        $I->see('You have made changes to the database. Rows affected: 1', $mainPage::$resultSQL);

        $sql2 = "SELECT * FROM Customers WHERE CustomerName = 'WhiteBear' AND ContactName = 'Mikhail Shishkin'";
        $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql2 . '");');
        $I->click($mainPage::$runButton);

        $tableValue = $I->grabMultiple($mainPage::$table);
        unset($tableValue[0]);
        foreach ($tableValue as $row) {
            if (strpos($row, $searchRow) !== false) {
                $result = true;
            };
        }
        assertCount(1, array_count_values($tableValue), 'The number of rows in the table does not match the expected');
        assertTrue($result, 'There are no entries in the table that match = ' . $searchRow . '');
    }

    public function fourthTest(AcceptanceTester $I, Main $mainPage)
    {
        $mainPage->prepare();

        $customerName = 'PolarBear';
        $contactName = 'ShishMish';
        $address = 'Agros, 7';
        $city = 'Limassol';
        $postalCode = '4866';
        $country = 'Cyprus';
        $customerID = 1;
        $sql = "UPDATE Customers SET CustomerName = '{$customerName}', ContactName = '{$contactName}', Address = '{$address}', City = '{$city}', PostalCode = '{$postalCode}', Country = '{$country}' WHERE CustomerID = {$customerID}";

        $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql . '");');
        $I->click($mainPage::$runButton);
        $I->wait(3);
        $I->see('You have made changes to the database. Rows affected: 1', $mainPage::$resultSQL);

        $searchRow = "{$customerName} {$contactName}";
        $sql2 = "SELECT * FROM Customers WHERE CustomerID = {$customerID}";
        $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql2 . '");');
        $I->click($mainPage::$runButton);

        $tableValue = $I->grabMultiple($mainPage::$table);
        unset($tableValue[0]);
        foreach ($tableValue as $row) {
            if (strpos($row, $searchRow) !== false) {
                $result = true;
            };
        }
        assertCount(1, array_count_values($tableValue), 'The number of rows in the table does not match the expected');
        assertTrue($result, 'There are no entries in the table that match = ' . $searchRow . '');
    }

    public function fifthTest(AcceptanceTester $I, Main $mainPage)
    {
        $mainPage->prepare();
        $I->click($mainPage::$restoreDB);
        $I->acceptPopup();

        $sql = 'SELECT * FROM Customers';
        $txtArea = $I->executeJS('return window.editor.getValue();');
        if (strpos($txtArea, $sql) !== false) {
            $I->click($mainPage::$runButton);
        } else {
            $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql . '");');
            $I->click($mainPage::$runButton);
        }
        $I->wait(1);

        $tableValueFirst = $I->grabMultiple($mainPage::$table);
        unset($tableValueFirst[0]);

        $sql2 = "INSERT INTO Customers (CustomerName, ContactName, Address, City, PostalCode, Country) VALUES ('WhiteBear', 'Mikhail Shishkin', '2nd Mirgorodskaya, 9', 'Novosibirsk','630058', 'Russian Federation')";
        $searchRow = 'WhiteBear Mikhail Shishkin 2nd Mirgorodskaya';
        $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql2 . '");');
        $I->click($mainPage::$runButton);
        $I->wait(1);
        $I->see('You have made changes to the database. Rows affected: 1', $mainPage::$resultSQL);

        $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql . '");');
        $I->click($mainPage::$runButton);
        $I->wait(1);
        $tableValueSecond = $I->grabMultiple($mainPage::$table);
        unset($tableValueSecond[0]);

        foreach ($tableValueSecond as $row) {
            if (strpos($row, $searchRow) !== false) {
                $result = true;
            };
        }
        assertCount(92, array_count_values($tableValueSecond), 'The number of rows in the table does not match the expected');
        assertTrue($result, 'There are no entries in the table that match = ' . $searchRow . '');

        $sql3 = "DELETE FROM Customers WHERE CustomerID = " . count($tableValueSecond) . ";";

        $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql3 . '");');
        $I->click($mainPage::$runButton);
        $I->wait(1);
        $I->see('You have made changes to the database. Rows affected: 1', $mainPage::$resultSQL);

        $I->executeJS('window.editor.setCursor(editor.firstLine()); window.editor.setValue("' . $sql . '");');
        $I->click($mainPage::$runButton);
        $I->wait(1);
        $tableValueThird = $I->grabMultiple($mainPage::$table);
        unset($tableValueThird[0]);

        assertEquals($tableValueFirst, $tableValueThird, 'Tables not Equal');
    }
}

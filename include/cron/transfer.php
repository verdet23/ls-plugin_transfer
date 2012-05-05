#!/usr/bin/env php
<?php
$sDirRoot = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
set_include_path(get_include_path() . PATH_SEPARATOR . $sDirRoot);
chdir($sDirRoot);
$_SERVER['HTTP_HOST'] = '';
require_once($sDirRoot . "/config/loader.php");
require_once($sDirRoot . "/engine/classes/Cron.class.php");

class TransferCron extends Cron
{

    protected $aActivePlugins = array();
    protected $sOldHost = '';
    protected $sNewHost = '';

    /**
     * Construct
     *
     * @param string $sLockFile
     * @param array $argv
     */
    public function __construct($sLockFile = null, $argv = array())
    {
        if (!isset($argv[1]) || !isset($argv[2])) {
            echo 'Domains not specified!' . PHP_EOL;
            exit;
        }

        $this->sOldHost = 'http://' . $argv[1];
        $this->sNewHost = 'http://' . $argv[2];

        parent::__construct($sLockFile);
    }

    public function Client()
    {
        $this->aActivePlugins = $this->Plugin_GetActivePlugins();

        if (!in_array('transfer', $this->aActivePlugins)) {
            echo 'Plugin Transfer not activated!' . PHP_EOL;
            exit;
        }
        $aEngineTables = Config::Get('plugin.transfer.engine');
        echo 'Starting update engine tables' . PHP_EOL;
        ob_flush();
        foreach ($aEngineTables as $sConfigTableName => $aFields) {
            $this->UpdateTable($sConfigTableName, $aFields);
        }
        echo 'Starting update plugins tables' . PHP_EOL;
        ob_flush();
        $aPluginsData = Config::Get('plugin.transfer.plugins');
        foreach ($aPluginsData as $sPluginName => $aPluginsTables) {
            if (in_array($sPluginName, $this->aActivePlugins)) {
                echo 'Plugin ' . $sPluginName . ' activated, begin update' . PHP_EOL;
                ob_flush();
                foreach ($aPluginsTables as $sConfigTableName => $aFields) {
                    $this->UpdateTable($sConfigTableName, $aFields);
                }
            }
        }
    }

    /**
     * Update table
     *
     * @param string $sConfigTableName
     * @param array $aFields
     * @return void
     */
    protected function UpdateTable($sConfigTableName, $aFields)
    {
        $sTableName = Config::Get($sConfigTableName);
        if (!$sTableName || !$this->Database_isTableExists($sTableName)) {
            echo 'Table ' . $sConfigTableName . ' not exist' . PHP_EOL;
            ob_flush();
            return;
        }
        foreach ($aFields as $sField) {
            if (!$this->Database_isFieldExists($sTableName, $sField)) {
                echo 'Field ' . $sField . ' not exist in table ' . $sConfigTableName . PHP_EOL;
                ob_flush();
                continue;
            }

            $iCountRow = $this->PluginTransfer_Transfer_GetCountRowsToUpdate($sTableName, $sField, $this->sOldHost);
            echo 'Need to update ' . $iCountRow . ' in table ' . $sConfigTableName . ' field ' . $sField . PHP_EOL;
            ob_flush();
            if ($iCountRow) {
                $this->PluginTransfer_Transfer_UpdateField($sTableName, $sField, $this->sOldHost, $this->sNewHost);
                echo 'Field ' . $sField . ' in table ' . $sConfigTableName . ' updated!' . PHP_EOL;
                ob_flush();
            }
        }
    }

}

$sLockFilePath = Config::Get('sys.cache.dir') . 'transfer.lock';

$app = new TransferCron($sLockFilePath, $argv);
print $app->Exec();

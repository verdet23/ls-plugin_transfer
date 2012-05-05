<?php

class PluginTransfer_ModuleTransfer extends Module
{

    /**
     *
     * @var PluginTransfer_ModuleTransfer_MapperTransfer
     */
    protected $oMapper;

    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Get count rows in table to update
     *
     * @param string $sTableName
     * @param string $sField
     * @param string $sOldHost
     * @return int
     */
    public function GetCountRowsToUpdate($sTableName, $sField, $sOldHost)
    {
        return $this->oMapper->GetCountRowsToUpdate($sTableName, $sField, $sOldHost);
    }

    /**
     * Update field
     * 
     * @param string $sTableName
     * @param string $sField
     * @param string $sOldHost
     * @param string $sNewHost
     * @return boolean
     */
    public function UpdateField($sTableName, $sField, $sOldHost, $sNewHost)
    {
        return $this->oMapper->UpdateField($sTableName, $sField, $sOldHost, $sNewHost);
    }

}
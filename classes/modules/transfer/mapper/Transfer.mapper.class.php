<?php

class PluginTransfer_ModuleTransfer_MapperTransfer extends Mapper
{

    /**
     *
     * @var DbSimple_Generic_Database
     */
    protected $oDb;

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
        $sql = "SELECT
                    COUNT(*) as count
                FROM
                    " . $this->oDb->escape($sTableName, true) . "
                WHERE
                    " . $this->oDb->escape($sField, true) . " LIKE '%" . mysql_real_escape_string($sOldHost) . "%'
            ";

        if ($aRow = $this->oDb->selectRow($sql)) {
            return $aRow['count'];
        }
        return 0;
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

        $sql = "UPDATE
                    " . $this->oDb->escape($sTableName, true) . "
                SET " . $this->oDb->escape($sField, true) . " = REPLACE(
                        " . $this->oDb->escape($sField, true) . ",
                        " . $this->oDb->escape($sOldHost) . ",
                        " . $this->oDb->escape($sNewHost) . "
                    )
                WHERE
                    " . $this->oDb->escape($sField, true) . "
                        LIKE '%" . mysql_real_escape_string($sOldHost) . "%'
                ";

        return $this->oDb->query($sql);
    }

}

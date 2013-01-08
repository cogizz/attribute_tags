<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package	   MetaModels
 * @subpackage AttributeTags
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */
if (!defined('TL_ROOT'))
{
	die('You cannot access this file directly!');
}

/**
 * Supplementary class for handling DCA information for select attributes.
 *
 * @package	   MetaModels
 * @subpackage AttributeTags
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 */
class TableMetaModelsAttributeTags extends TableMetaModelAttribute
{
	/**
	 * @var TableMetaModelRenderSetting
	 */
	protected static $objInstance = null;

	/**
	 * Get the static instance.
	 *
	 * @static
	 * @return MetaPalettes
	 */
	public static function getInstance()
	{
		if (self::$objInstance == null) {
			self::$objInstance = new TableMetaModelsAttributeTags();
		}
		return self::$objInstance;
	}

	public function getTableNames()
	{
		$objDB = Database::getInstance();
		return $objDB->listTables();
	}

	public function getColumnNames(DataContainer $objDC)
	{
		$arrFields = array();

		if (($objDC->getCurrentModel())
		    && $this->Database->tableExists($objDC->getCurrentModel()->getProperty('tag_table')))
		{
			foreach ($this->Database->listFields($objDC->getCurrentModel()->getProperty('tag_table')) as $arrInfo)
			{
				if ($arrInfo['type'] != 'index')
				{
					$arrFields[$arrInfo['name']] = $arrInfo['name'];
				}
			}
		}

		return $arrFields;
	}

	public function getIntColumnNames(DataContainer $objDC)
	{
		$arrFields = array();

		if (($objDC->getCurrentModel())
		    && $this->Database->tableExists($objDC->getCurrentModel()->getProperty('tag_table')))
		{
			foreach ($this->Database->listFields($objDC->getCurrentModel()->getProperty('tag_table')) as $arrInfo)
			{
				if ($arrInfo['type'] != 'index' && $arrInfo['type'] == 'int')
				{
					$arrFields[$arrInfo['name']] = $arrInfo['name'];
				}
			}
		}

		return $arrFields;
	}
}

?>
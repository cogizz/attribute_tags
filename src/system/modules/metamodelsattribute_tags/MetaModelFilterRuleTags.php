<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package    MetaModels
 * @subpackage AttributeTags
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

/**
 * This is the MetaModelFilterRule class for handling select fields.
 * 
 * @package	   MetaModels
 * @subpackage AttributeTags
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 */
class MetaModelFilterRuleTags extends MetaModelFilterRule
{

	public function __construct(MetaModelAttributeTags $objAttribute, $strValue)
	{
		parent::__construct();
		$this->objAttribute = $objAttribute;
		$this->value = $strValue;
	}

	public function sanitizeValue()
	{
		$strTableNameId = $this->objAttribute->get('tag_table');
		$strColNameId = $this->objAttribute->get('tag_id');
		$strColNameAlias = $this->objAttribute->get('tag_alias');
		
		$arrValues = is_array($this->value) ? $this->value : explode(',', $this->value);

		$objDB = Database::getInstance();

		if ($strColNameAlias)
		{
			$objSelectIds = $objDB
				->prepare(
					sprintf('SELECT %1$s FROM %2$s WHERE %3$s IN (%4$s)',
						$strColNameId,
						$strTableNameId,
						$strColNameAlias,
						implode(',', array_fill(0, count($arrValues), '?'))
					)
				)
				->execute($arrValues);

			$arrValues = $objSelectIds->fetchEach($strColNameId);
		} else {
			$arrValues = array_map('intval', $arrValues);
		}
		return $arrValues;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMatchingIds()
	{
		$arrValues = $this->sanitizeValue();

		// Get out when no values are available.
		if (!$arrValues)
		{
			return array();
		}

		$objDB = Database::getInstance();
		$objMatches = $objDB
			->prepare('SELECT item_id as id FROM tl_metamodel_tag_relation WHERE value_id IN (' . implode(',', $arrValues) . ') AND att_id = ?')
			->execute($this->objAttribute->get('id'));
		return $objMatches->fetchEach('id');
	}
}

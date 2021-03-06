<?php
require_once ROOT_DIR . '/services/Admin/ObjectEditor.php';
require_once ROOT_DIR . '/sys/File/ImageUpload.php';

class WebBuilder_Images extends ObjectEditor
{
	function getObjectType()
	{
		return 'ImageUpload';
	}

	function getToolName()
	{
		return 'Images';
	}

	function getModule()
	{
		return 'WebBuilder';
	}

	function getPageTitle()
	{
		return 'Uploaded Images';
	}

	function getAllObjects()
	{
		$object = new ImageUpload();
		$object->type = 'web_builder_image';
		$object->orderBy('title');
		$object->find();
		$objectList = array();
		while ($object->fetch()) {
			$objectList[$object->id] = clone $object;
		}
		return $objectList;
	}

	function updateFromUI($object, $structure){
		$object->type = 'web_builder_image';
		return parent::updateFromUI($object, $structure);
	}

	function getObjectStructure()
	{
		$objectStructure = ImageUpload::getObjectStructure();
		unset($objectStructure['type']);
		return $objectStructure;
	}

	function getPrimaryKeyColumn()
	{
		return 'id';
	}

	function getIdKeyColumn()
	{
		return 'id';
	}

	/**
	 * @param FileUpload $existingObject
	 * @return array
	 */
	function getAdditionalObjectActions($existingObject)
	{
		$objectActions = [];
		if (!empty($existingObject) && !empty($existingObject->id)){
			$objectActions[] = [
				'text' => 'View Image',
				'url' => '/WebBuilder/ViewImage?id=' . $existingObject->id,
			];
		}
		return $objectActions;
	}

	function getInstructions()
	{
		return '';
	}

	function getBreadcrumbs()
	{
		$breadcrumbs = [];
		$breadcrumbs[] = new Breadcrumb('/Admin/Home', 'Administration Home');
		$breadcrumbs[] = new Breadcrumb('/Admin/Home#web_builder', 'Web Builder');
		$breadcrumbs[] = new Breadcrumb('/WebBuilder/Images', 'Images');
		return $breadcrumbs;
	}

	function canView()
	{
		return UserAccount::userHasPermission(['Administer All Web Content']);
	}

	function getActiveAdminSection()
	{
		return 'web_builder';
	}
}
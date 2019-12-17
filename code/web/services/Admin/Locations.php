<?php

require_once ROOT_DIR . '/Action.php';
require_once ROOT_DIR . '/services/Admin/ObjectEditor.php';

class Locations extends ObjectEditor
{

	function getObjectType(){
		return 'Location';
	}
	function getToolName(){
		return 'Locations';
	}
	function getPageTitle(){
		return 'Locations (Branches)';
	}
	function getAllObjects(){
		//Look lookup information for display in the user interface
		$user = UserAccount::getLoggedInUser();

		$location = new Location();
		$location->orderBy('displayName');
		if (UserAccount::userHasRole('locationManager')){
			$location->locationId = $user->homeLocationId;
		} else if (!UserAccount::userHasRole('opacAdmin')){
			//Scope to just locations for the user based on home library
			$patronLibrary = Library::getLibraryForLocation($user->homeLocationId);
			$location->libraryId = $patronLibrary->libraryId;
		}
		$location->find();
		$locationList = array();
		while ($location->fetch()){
			$locationList[$location->locationId] = clone $location;
		}
		return $locationList;
	}

	function getObjectStructure(){
		return Location::getObjectStructure();
	}

	function getPrimaryKeyColumn(){
		return 'code';
	}

	function getIdKeyColumn(){
		return 'locationId';
	}
	function getAllowableRoles(){
		return array('opacAdmin', 'libraryAdmin', 'libraryManager', 'locationManager');
	}
	function canAddNew(){
		$user = UserAccount::getLoggedInUser();
		return UserAccount::userHasRole('opacAdmin');
	}
	function canDelete(){
		$user = UserAccount::getLoggedInUser();
		return UserAccount::userHasRole('opacAdmin');
	}
	function getAdditionalObjectActions($existingObject){
		$user = UserAccount::getLoggedInUser();
 		$objectActions = array();
		if ($existingObject != null){
			$objectActions[] = array(
				'text' => 'Reset Facets To Default',
				'url' => '/Admin/Locations?objectAction=resetFacetsToDefault&amp;id=' . $existingObject->locationId,
			);
			$objectActions[] = array(
				'text' => 'Reset More Details To Default',
				'url' => '/Admin/Locations?id=' . $existingObject->locationId . '&amp;objectAction=resetMoreDetailsToDefault',
			);
			if (!UserAccount::userHasRole('libraryManager') && !UserAccount::userHasRole('locationManager')){
				$objectActions[] = array(
						'text' => 'Copy Location Data',
						'url' => '/Admin/Locations?id=' . $existingObject->locationId . '&amp;objectAction=copyDataFromLocation',
				);
			}
		}else{
			echo("Existing object is null");
		}
		return $objectActions;
	}

	function copyDataFromLocation(){
		$locationId = $_REQUEST['id'];
		if (isset($_REQUEST['submit'])){
			$location = new Location();
			$location->locationId = $locationId;
			$location->find(true);

			$locationToCopyFromId = $_REQUEST['locationToCopyFrom'];
			$locationToCopyFrom = new Location();
			$locationToCopyFrom->locationId = $locationToCopyFromId;
			$location->find(true);

			if (isset($_REQUEST['copyBrowseCategories'])){
				$location->clearBrowseCategories();

				$browseCategoriesToCopy = $locationToCopyFrom->browseCategories;
				foreach ($browseCategoriesToCopy as $key => $category){
					$category->locationId = $locationId;
					$category->id = null;
					$browseCategoriesToCopy[$key] = $category;
				}
				$location->browseCategories = $browseCategoriesToCopy;
			}
			$location->update();
			header("Location: /Admin/Locations?objectAction=edit&id=" . $locationId);
		}else{
			//Prompt user for the location to copy from
			$allLocations = $this->getAllObjects();

			unset($allLocations[$locationId]);
			foreach ($allLocations as $key => $location){
				if (count($location->facets) == 0 && count($location->browseCategories) == 0){
					unset($allLocations[$key]);
				}
			}
			global $interface;
			$interface->assign('allLocations', $allLocations);
			$interface->assign('id', $locationId);
			$interface->setTemplate('../Admin/copyLocationFacets.tpl');
		}
	}

	function resetMoreDetailsToDefault(){
		$location = new Location();
		$locationId = $_REQUEST['id'];
		$location->locationId = $locationId;
		if ($location->find(true)){
			$location->clearMoreDetailsOptions();

			$defaultOptions = array();
			require_once ROOT_DIR . '/RecordDrivers/RecordInterface.php';
			$defaultMoreDetailsOptions = RecordInterface::getDefaultMoreDetailsOptions();
			$i = 0;
			foreach ($defaultMoreDetailsOptions as $source => $defaultState){
				$optionObj = new LocationMoreDetails();
				$optionObj->locationId = $locationId;
				$optionObj->collapseByDefault = $defaultState == 'closed';
				$optionObj->source = $source;
				$optionObj->weight = $i++;
				$defaultOptions[] = $optionObj;
			}

			$location->moreDetailsOptions = $defaultOptions;
			$location->update();

			$_REQUEST['objectAction'] = 'edit';
		}
		$structure = $this->getObjectStructure();
		header("Location: /Admin/Locations?objectAction=edit&id=" . $locationId);
	}

	function getInstructions(){
		return 'For more information about Location Setting configuration, see the <a href="https://docs.google.com/document/d/1DO7DfrslDm2DXUYul0hKAh8u5pGmjwyQABVdmb6javM/edit?ts=5696ca39">online documentation</a>.';
	}
}
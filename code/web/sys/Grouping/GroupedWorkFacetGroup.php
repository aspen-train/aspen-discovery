<?php
require_once ROOT_DIR . '/sys/Grouping/GroupedWorkFacet.php';

class GroupedWorkFacetGroup extends DataObject
{
	public $__table = 'grouped_work_facet_groups';
	public $id;
	public $name;

	public $__facets;

	static function getObjectStructure(){
		$facetSettingStructure = GroupedWOrkFacet::getObjectStructure();
		unset($facetSettingStructure['weight']);
		unset($facetSettingStructure['facetGroupId']);
		unset($facetSettingStructure['numEntriesToShowByDefault']);
		unset($facetSettingStructure['showAsDropDown']);

		$structure = [
			'id' => array('property' => 'id', 'type' => 'label', 'label' => 'Id', 'description' => 'The unique id within the database'),
			'name' => array('property' => 'name', 'type' => 'text', 'label' => 'Display Name', 'description' => 'The name of the settings', 'size' => '40', 'maxLength'=>255),
			'facets' => array(
				'property' => 'facets',
				'type' => 'oneToMany',
				'label' => 'Facets',
				'description' => 'A list of facets to display in search results',
				'helpLink' => 'https://docs.google.com/document/d/1DIOZ-HCqnrBAMFwAomqwI4xv41bALk0Z1Z2fMrhQ3wY',
				'keyThis' => 'libraryId',
				'keyOther' => 'libraryId',
				'subObjectType' => 'LibraryFacetSetting',
				'structure' => $facetSettingStructure,
				'sortable' => true,
				'storeDb' => true,
				'allowEdit' => true,
				'canEdit' => false,
				'additionalOneToManyActions' => array(
					array(
						'text' => 'Copy Library Facets',
						'url' => '/Admin/Libraries?id=$id&amp;objectAction=copyFacetsFromLibrary',
					),
					array(
						'text' => 'Reset Facets To Default',
						'url' => '/Admin/Libraries?id=$id&amp;objectAction=resetFacetsToDefault',
						'class' => 'btn-warning',
					),
				)
			),
		];
		return $structure;
	}

	function setupDefaultFacets($type){
		$defaultFacets = array();

		$facet = new GroupedWorkFacet();
		$facet->setupTopFacet('format_category', 'Format Category');
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupTopFacet('availability_toggle', 'Available?');
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('available_at', 'Available Now At', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('format', 'Format', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$facet->multiSelect = true;
		$facet->canLock = true;
		$defaultFacets[] = $facet;

		if ($type == 'academic'){
			$facet = new GroupedWorkFacet();
			$facet->setupSideFacet('literary_form', 'Literary Form', true);
			$facet->facetGroupId = $this->id;
			$facet->weight = count($defaultFacets) + 1;
			$facet->canLock = true;
			$defaultFacets[] = $facet;
		}else{
			$facet = new GroupedWorkFacet();
			$facet->setupSideFacet('literary_form', 'Fiction / Non-Fiction', true);
			$facet->facetGroupId = $this->id;
			$facet->weight = count($defaultFacets) + 1;
			$facet->multiSelect = true;
			$facet->canLock = true;
			$defaultFacets[] = $facet;
		}

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('target_audience', 'Reading Level', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$facet->numEntriesToShowByDefault = 8;
		$facet->multiSelect = true;
		$facet->canLock = true;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('topic_facet', 'Subject', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$facet->multiSelect = true;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('time_since_added', 'Added in the Last', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('authorStr', 'Author', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('series_facet', 'Series', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupAdvancedFacet('awards_facet', 'Awards');
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupAdvancedFacet('era', 'Era');
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('genre_facet', 'Genre', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('itype', 'Item Type', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$facet->multiSelect = true;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('language', 'Language', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupAdvancedFacet('mpaa_rating', 'Movie Rating');
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$facet->multiSelect = true;
		$defaultFacets[] = $facet;

		if ($type == 'consortium') {
			$facet = new GroupedWorkFacet();
			$facet->setupAdvancedFacet('owning_library', 'Owning System');
			$facet->facetGroupId = $this->id;
			$facet->weight = count($defaultFacets) + 1;
			$defaultFacets[] = $facet;

			$facet = new GroupedWorkFacet();
			$facet->setupAdvancedFacet('owning_location', 'Owning Branch');
			$facet->facetGroupId = $this->id;
			$facet->weight = count($defaultFacets) + 1;
			$defaultFacets[] = $facet;
		}

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('econtent_source', 'eContent Collection', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$facet->multiSelect = true;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('publishDate', 'Publication Date', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupAdvancedFacet('geographic_facet', 'Region');
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		$facet = new GroupedWorkFacet();
		$facet->setupSideFacet('rating_facet', 'User Rating', true);
		$facet->facetGroupId = $this->id;
		$facet->weight = count($defaultFacets) + 1;
		$defaultFacets[] = $facet;

		if ($type != 'academic'){
			$facet = new GroupedWorkFacet();
			$facet->setupSideFacet('accelerated_reader_interest_level', 'AR Interest Level', true);
			$facet->facetGroupId = $this->id;
			$facet->weight = count($defaultFacets) + 1;
			$defaultFacets[] = $facet;

			$facet = new GroupedWorkFacet();
			$facet->setupSideFacet('accelerated_reader_reading_level', 'AR Reading Level', true);
			$facet->facetGroupId = $this->id;
			$facet->weight = count($defaultFacets) + 1;
			$defaultFacets[] = $facet;

			$facet = new GroupedWorkFacet();
			$facet->setupSideFacet('accelerated_reader_point_value', 'AR Point Value', true);
			$facet->facetGroupId = $this->id;
			$facet->weight = count($defaultFacets) + 1;
			$defaultFacets[] = $facet;
		}

		$this->__facets = $defaultFacets;
		$this->update();
	}

	public function update(){
		$ret = parent::update();
		if ($ret !== FALSE ){
			$this->saveFacets();
		}
		return $ret;
	}

	public function insert(){
		$ret = parent::insert();
		if ($ret !== FALSE ){
			$this->saveFacets();
		}
		return $ret;
	}

	public function saveFacets(){
		if (isset ($this->__facets) && is_array($this->__facets)){
			$this->saveOneToManyOptions($this->__facets, 'facetGroupId');
			unset($this->facets);
		}
	}

	public function __get($name)
	{
		if ($name == 'facets'){
			return $this->getFacets();
		}else{
			return $this->_data[$name];
		}
	}

	public function __set($name, $value)
	{
		if ($name == 'facets'){
			$this->setFacets($value);
		}else{
			$this->_data[$name] = $value;
		}
	}

	public function getFacets(){
		if (!isset($this->__facets) && $this->id){
			$this->__facets = array();
			$facet = new GroupedWorkFacet();
			$facet->facetGroupId = $this->id;
			$facet->orderBy('weight');
			$facet->find();
			while($facet->fetch()){
				$this->__facets[$facet->id] = clone($facet);
			}
		}
		return $this->__facets;
	}

	public function setFacets($value){
		$this->__facets = $value;
	}

	public function clearFacets(){
		$this->clearOneToManyOptions('GroupedWorkFacet', 'facetGroupId');
		/** @noinspection PhpUndefinedFieldInspection */
		$this->facets = array();
	}
}
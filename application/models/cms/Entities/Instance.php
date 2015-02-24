<?php
namespace Entities;
use \Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity(repositoryClass="Repositories\Business_obj_repository")
 * @Table(name="instances")
 */
class Instance {
	 /**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
    public $id;

	/**
	 * @OneToMany(targetEntity="Instance_value", mappedBy="instance", cascade={"persist"})
	 *
	 **/
	public $fields;

	public function __construct() {
		$this->fields = new ArrayCollection();
	}

	function setFields($fields) {
		$this->fields = new ArrayCollection($fields);
	}
}
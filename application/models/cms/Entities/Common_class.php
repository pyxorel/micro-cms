<?php
namespace Entities;
use \Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity(repositoryClass="Repositories\Business_obj_repository")
 * @Table(name="common_class")
 */
class Common_class {
	 /**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
    public $id;
	/** @Column(type="string", name="name", length=50) */
	public $name;
	/** @Column(type="string", name="loc_name", length=50) */
	public $loc_name;
	/**
	 * @OneToMany(targetEntity="Common_class_field_link", mappedBy="common_class")
	 * @OrderBy({"order" = "DESC"})
     */
	public $links;
	/**
	 * @ManyToMany(targetEntity="Common_class_field")
	 * @JoinTable(name="common_class_field_link",
	 *   joinColumns={@JoinColumn(name="id_com_class", referencedColumnName="id")},
	 *   inverseJoinColumns={@JoinColumn(name="id_field", referencedColumnName="id")}
	 * )
	 */
	public $fields;

	function setLinks($links) {
		$this->$links = new ArrayCollection($links);
	}

	function setFields($fields) {
		$this->fields = new ArrayCollection($fields);
	}

	function get_fields() {
		$fields = array_map(
			function ($link) {
				return $link->__field;
			},
			$this->links->toArray()
		);

		return $fields;
	}

	public function __construct()
	{
		$this->fields = new ArrayCollection();
		//$this->$links = new ArrayCollection();
	}
}
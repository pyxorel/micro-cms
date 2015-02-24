<?php
namespace Entities;
use \Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity(repositoryClass="Repositories\Business_obj_repository", readOnly=true)
 * @Table(name="view_instance")
 *
 */
class Instance_view {
	/**
	 *
	 * @Column(type="integer", name="id_link")
	 **/
	public $id_link;
	/** @Column(type="integer", name="id_inst")
	 * @Id
	 */
    public $id;
	/** @Column(type="integer", name="_order") */
	public $order;
	/** @Column(type="string", name="value") */
	public $value;
	/** @Column(type="string", name="type") */
	public $type;
	/** @Column(type="string", name="field_name") */
	public $field_name;
	/** @Column(type="string", name="field_loc_name") */
	public $field_loc_name;
	/** @Column(type="string", name="class_name") */
	public $class_name;
	/** @Column(type="string", name="unit") */
	public $unit;
	/** @Column(type="string", name="extra") */
	public $extra;
	/** @Column(type="integer", name="id_field") */
	public $id_field;
	/**
	 * @Id
	 * @Column(type="integer", name="id_value") */
	public $id_value;
	public $fields;
}


class Instance_obj {
	public $id;
	public $class_name;
	public $fields;
}
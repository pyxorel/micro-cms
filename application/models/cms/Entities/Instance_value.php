<?php
namespace Entities;
/**
 * @Entity(repositoryClass="Repositories\Business_obj_repository")
 * @Table(name="instance_value")
 */
class Instance_value {

	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	public $id;

	/**
	 * @Column(type="string", name="value", length=2048)
	 */
	public $value;

	/**
	 * @OneToOne(targetEntity="Common_class_field_link", cascade={"persist"})
	 * @JoinColumn(name="id_link", referencedColumnName="id")
	 *
	 **/
	public $link;

	/**
	 * @ManyToOne(targetEntity="Instance", inversedBy="fields", cascade={"persist"})
	 * @JoinColumn(name="id_inst", referencedColumnName="id")
	 **/
	public $instance;

	public function get_field()
	{
		$this->link->__load();
		$this->link->__field->__load();
		return $this->link->__field;
	}


}
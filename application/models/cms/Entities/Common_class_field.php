<?php
namespace Entities;
/**
 * @Entity(repositoryClass="Repositories\Business_obj_repository")
 * @Table(name="common_class_field")
 */
class Common_class_field
{
    public static $TYPES = ['int' => 'int', 'decimal' => 'decimal', 'string' => 'string',
        'multi-string' => 'multi-string', 'file' => 'file', 'img' => 'img', 'bool'=>'bool', 'date'=>'date', 'link'=>'link', 'select'=>'select'];
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
    /** @Column(type="string", name="type", columnDefinition="ENUM('int', 'decimal', 'string', 'multi-string', 'file', 'img', 'bool', 'date', 'link'=>'link', 'select'=>'select')") */
    public $type;
    /** @Column(type="string", name="extra", length=255) */
    public $extra;
    /** @Column(type="string", name="unit", length=25) */
    public $unit;

    public function get_id()
    {
        return $this->id;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function get_extra()
    {
        return $this->extra;
    }

    public function get_unit()
    {
        return $this->unit;
    }

    public function get_loc_name()
    {
        return $this->loc_name;
    }

    /**
     * @OneToMany(targetEntity="Common_class_field_link", mappedBy="__field")
     */
    public $links;

}
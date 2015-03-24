<?php
namespace Entities;

use \Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Repositories\Business_obj_repository")
 * @Table(name="common_class_field_link")
 */
class Common_class_field_link
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    public $id;
    /** @Column(type="integer", name="id_com_class") */
    public $id_com_class;
    /** @Column(type="integer", name="id_field") */
    public $id_field;
    /**
     * @Column(type="integer", name="_order")
     */
    public $order;
    /**
     * @Column(type="string", name="rules")
     */
    public $rules;
    /**
     *
     * @ManyToOne(targetEntity="Common_class", inversedBy="links")
     * @JoinColumn(name="id_com_class", referencedColumnName="id", nullable=FALSE)
     */
    public $common_class;

    /**
     * @ManyToOne(targetEntity="Common_class_field", inversedBy="links")
     * @JoinColumn(name="id_field", referencedColumnName="id", nullable=FALSE)
     */
    public $__field;
}
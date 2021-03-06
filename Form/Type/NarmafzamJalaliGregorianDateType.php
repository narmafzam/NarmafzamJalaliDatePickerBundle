<?php
/**
 * This file is part of cardioclinic
 * Copyrighted by Narmafzam (Farzam Webnegar Sivan Co.), info@narmafzam.com
 * Created by peyman
 * Date: 08/29/2018
 */

namespace Narmafzam\JalaliDateBundle\Form\Type;

use Narmafzam\JalaliDateBundle\Form\DataTransformer\NarmafzamDateTransformer;
use Narmafzam\JalaliDateBundle\Model\Converter\DateConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NarmafzamJalaliGregorianDateType
 * @package Narmafzam\JalaliDateBundle\Form\Type
 */
class NarmafzamJalaliGregorianDateType extends AbstractType
{
    /**
     * @var DateConverter
     */
    private $dateConverter;

    /**
     * @var string
     */
    protected $locale;

    /**
     * NarmafzamJalaliDateType constructor.
     *
     * @param DateConverter $dateConverter
     * @param RequestStack $requestStack
     */
    public function __construct(DateConverter $dateConverter, RequestStack $requestStack)
    {
        $this->dateConverter = $dateConverter;
        $this->locale = $requestStack->getCurrentRequest()->getLocale() == 'fa' ? 'fa' : 'en';
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];
        $transformer = new NarmafzamDateTransformer($this->dateConverter, $options['serverFormat'], !empty($locale) ? $locale : $this->getLocale());
        $builder->addModelTransformer($transformer);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'serverFormat' => 'yyyy/MM/dd',
            'locale' => $this->locale,
            'parent_attr' => array(
                'class'   => 'col-sm-6'
            )
        ));

        $resolver->setAllowedTypes('serverFormat', ['string', 'null']);
        $resolver->setAllowedTypes('locale', ['string', 'null']);
        $resolver->setAllowedValues('locale', ['fa', 'en', null]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'narmafzam_jalali_gregorian_date';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
<?php

namespace AppBundle\Form\EditPlayer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type: PlayerForm
 *
 * @package AppBundle\Form\EditPlayer
 */
class PlayerForm extends AbstractType
{
    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PlayerEntity::class
        ]);
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'app.player-edit.form.name'
        ]);

        $builder->add('email', EmailType::class, [
            'label' => 'app.player-edit.form.email'
        ]);

        $builder->add('url', UrlType::class, [
            'label' => 'app.player-edit.form.url'
        ]);

        $builder->add('positionY', IntegerType::class, [
            'label' => 'app.player-edit.form.position-y'
        ]);

        $builder->add('positionX', IntegerType::class, [
            'label' => 'app.player-edit.form.position-x'
        ]);

        $builder->add('previousY', IntegerType::class, [
            'label' => 'app.player-edit.form.previous-y'
        ]);

        $builder->add('previousX', IntegerType::class, [
            'label' => 'app.player-edit.form.previous-x'
        ]);

        $builder->add('status', ChoiceType::class, [
            'choices'  => [
                'STATUS_REGULAR'    => 1,
                'STATUS_POWERED'    => 2,
                'STATUS_RELOADING'  => 4,
                'STATUS_KILLED'     => 8
            ],
            'expanded' => false,
            'multiple' => false,
            'label'    => 'app.player-edit.form.status'
        ]);

        $builder->add('statusCount', IntegerType::class, [
            'label' => 'app.player-edit.form.status-count'
        ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'app.player-edit.form.submit'
        ]);
    }
}

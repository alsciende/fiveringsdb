<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Pack;
use App\Enum\Clan;
use App\Enum\Type;
use App\Search\AdvancedCardSearch;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** @extends AbstractType<AdvancedCardSearch> */
class AdvancedCardSearchType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'card_search_name_input_label',
            ])
            ->add('clan', EnumType::class, [
                'class' => Clan::class,
                'placeholder' => 'card_search_clan_input_placeholder',
                'required' => false,
                'label' => 'card_search_clan_input_label',
            ])
            ->add('cost', IntegerType::class, [
                'required' => false,
                'label' => 'card_search_cost_input_label',
            ])
            ->add('type', EnumType::class, [
                'class' => Type::class,
                'placeholder' => 'card_search_type_input_placeholder',
                'required' => false,
                'label' => 'card_search_type_input_label',
            ])
            ->add('trait', TextType::class, [
                'required' => false,
                'label' => 'card_search_trait_input_label',
            ])
            ->add('text', TextType::class, [
                'required' => false,
                'label' => 'card_search_text_input_label',
            ])
            ->add('illustrator', TextType::class, [
                'required' => false,
                'label' => 'card_search_illustrator_input_label',
            ])
            ->add('pack', EntityType::class, [
                'class' => Pack::class,
                'query_builder' => fn (EntityRepository $er): QueryBuilder => $er->createQueryBuilder('p')->setCacheable(true),
                'placeholder' => 'card_search_pack_input_placeholder',
                'required' => false,
                'label' => 'card_search_pack_input_label',
            ])
            ->add('search', SubmitType::class, [
                'label' => 'card_search_search_button_label',
            ]);
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdvancedCardSearch::class,
        ]);
    }
}

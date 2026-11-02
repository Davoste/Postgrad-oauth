<?php

namespace App\Models\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ThesisState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(ConceptDevelopment::class)
            // Standard forward transitions
            ->allowTransition(ConceptDevelopment::class,  ProposalDevelopment::class)
            ->allowTransition(ProposalDevelopment::class, EthicalClearance::class)
            ->allowTransition(EthicalClearance::class,    DataCollection::class)
            ->allowTransition(DataCollection::class,      ThesisWriting::class)
            ->allowTransition(ThesisWriting::class,       IntentToSubmit::class)
            ->allowTransition(IntentToSubmit::class,      Submitted::class)
            ->allowTransition(Submitted::class,           Examination::class)
            ->allowTransition(Examination::class,         Graduated::class);
    }
}

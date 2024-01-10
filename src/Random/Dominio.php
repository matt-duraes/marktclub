<?php

namespace Random;

trait Dominio
{
    public function dominio(): string
    {
        $dominio = [
            'ficticioemail',
            'mailficticio',
            'emailcorp',
            'cybermail',
            'techmailpro',
            'fictiocommunications',
            'virtualmailgroup',
            'imaginemail',
            'digitalmailtech',
            'futuremailco',
            'unicorptechemail',
            'innovativemail',
            'dreamemailinc',
            'virtualtechmail',
            'techsolutionsmail',
            'cybercommmail',
            'cloudmailpro',
            'quantumemailtech',
            'infomailgroup',
            'wizardmail',
            'ecomailpro',
            'imaginatemail',
            'digitalworldmail',
            'futuramailtech',
            'mailgeniustech',
            'innovativemailinc',
            'dreamtechemail',
            'virtualmailsolutions',
            'techwavecorp',
            'futuremailtech',
            'unicorpsolutionsmail',
            'innovativetechmail',
            'dreammailco',
            'virtualtechsolutions',
            'technowizardmail',
            'cloudgeniusemail',
            'infomailtech',
            'quantummailpro',
            'ecomailtech',
            'virtualimagemail',
            'techfuturamail',
            'digitaldreammail',
            'cloudtechsolutions',
            'innovativemailgenius',
            'wizardmailcorp',
            'dreammailsolutions',
            'futuretechmail',
            'virtualmailwizard',
            'techgeniuscorp',
            'imaginativemailtech'
        ];
        $final = ['.com', '.net', '.com.br', '.club', '.net.br'];
        return $dominio[rand(0, count($dominio) - 1)] . '.' . $final[rand(0, count($final) - 1)];
    }
}

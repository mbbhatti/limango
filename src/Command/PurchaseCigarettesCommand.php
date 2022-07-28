<?php

namespace App\Command;

use App\Machine\CigaretteMachine;
use App\Machine\PurchaseTransaction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CigaretteMachine
 * @package App\Command
 */
class PurchaseCigarettesCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('packs', InputArgument::REQUIRED, "How many packs do you want to buy?");
        $this->addArgument('amount', InputArgument::REQUIRED, "The amount in euro.");
    }

    /**
     * @param InputInterface   $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $itemCount = (int) $input->getArgument('packs');
        $amount = (float) \str_replace(',', '.', $input->getArgument('amount'));

        $cigaretteMachine = new CigaretteMachine();
        $purchaseTransaction = new PurchaseTransaction($itemCount, $amount);
        $purchasedItem = $cigaretteMachine->execute($purchaseTransaction);

        $message = sprintf(
            'You bought <info>%s</info> packs of cigarettes for <info>%s</info>, each for <info>%s</info>. ',
            $purchasedItem->getItemQuantity(),
            $purchasedItem->getTotalAmount(),
            CigaretteMachine::ITEM_PRICE
        );
        $output->writeln($message);
        $output->writeln('Your change is:');

        $table = new Table($output);
        $table
            ->setHeaders(array('Coins', 'Count'))
            ->setRows($purchasedItem->getChange())
        ;
        $table->render();
    }
}

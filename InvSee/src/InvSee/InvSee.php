<?php
declare(strict_types=1);

namespace InvSee;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\item\ItemFactory;
use pocketmine\item\StringToItemParser;

class InvSee extends PluginBase {

    public function onEnable(): void {
        $this->getLogger()->info("InvSee (sans InvMenu) activé !");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (strtolower($command->getName()) !== "invsee") return false;

        if (!$sender instanceof Player) {
            $sender->sendMessage("§cCommande utilisable uniquement en jeu !");
            return true;
        }

        if (!$sender->hasPermission("invsee.command")) {
            $sender->sendMessage("§cVous n'avez pas la permission !");
            return true;
        }

        if (count($args) < 1) {
            $sender->sendMessage("§eUtilisation : /invsee <joueur>");
            return true;
        }

        $target = $this->getServer()->getPlayerExact($args[0]);
        if ($target === null) {
            $sender->sendMessage("§cJoueur introuvable ou hors ligne !");
            return true;
        }

        $sender->sendMessage("§6Inventaire de §e" . $target->getName() . "§6 :");

        $contents = $target->getInventory()->getContents();
        if (empty($contents)) {
            $sender->sendMessage("§7L'inventaire est vide.");
            return true;
        }

        foreach ($contents as $slot => $item) {
            $sender->sendMessage("§fSlot §b$slot§f : §a" . $item->getName() . " §7(x" . $item->getCount() . ")");
        }

        $sender->sendMessage("§8Utilise /invsee give <joueur> <item> <quantité> pour ajouter un objet.");
        return true;
    }
}

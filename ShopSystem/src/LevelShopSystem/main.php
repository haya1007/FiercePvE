<?php

namespace LevelShopSystem;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandExecutor;
use pocketmine\scheduler\Task;
//use pocketmine\scheduler\CallbackTask;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\PressurePlate;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\Attribute;
use pocketmine\entity\Effect;
use pocketmine\entity\Zombie;
use pocketmine\entity\Skeleton;
use pocketmine\entity\Enderman;
use pocketmine\entity\Villager;
use pocketmine\entity\PigZombie;
use pocketmine\entity\Creeper;
use pocketmine\entity\Spider;
use pocketmine\entity\Witch;
use pocketmine\entity\IronGolem;
use pocketmine\entity\Blaze;
use pocketmine\entity\Slime;
use pocketmine\entity\WitherSkeleton;
use pocketmine\entity\Horse;
use pocketmine\entity\Donkey;
use pocketmine\entity\Mule;
use pocketmine\entity\SkeletonHorse;
use pocketmine\entity\ZombieHorse;
use pocketmine\entity\Stray;
use pocketmine\entity\Husk;
use pocketmine\entity\Mooshroom;
use pocketmine\entity\FallingSand;
use pocketmine\entity\Item as DroppedItem;
use pocketmine\entity\Skin;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\ItemFrameDropItemEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityCombustByEntityEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryPickupArrowEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerTextPreSendEvent;
use pocketmine\event\player\PlayerAchievementAwardedEvent;
use pocketmine\event\player\PlayerAnimationEvent;
use pocketmine\event\player\PlayerBedEnterEvent;
use pocketmine\event\player\PlayerBedLeaveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerHungerChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerToggleSprintEvent;
use pocketmine\event\player\PlayerUseFishingRodEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\event\TextContainer;
use pocketmine\event\Timings;
use pocketmine\event\TranslationContainer;
use pocketmine\inventory\AnvilInventory;
use pocketmine\inventory\BaseTransaction;
use pocketmine\inventory\BigShapedRecipe;
use pocketmine\inventory\BigShapelessRecipe;
use pocketmine\inventory\CraftingManager;
use pocketmine\inventory\DropItemTransaction;
use pocketmine\inventory\EnchantInventory;
use pocketmine\inventory\FurnaceInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\FoodSource;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\item\Durable;
use pocketmine\level\ChunkLoader;
use pocketmine\level\Explosion;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\level\sound\LaunchSound;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\WeakPosition;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\metadata\MetadataValue;
use pocketmine\network\Network;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\AddHangingEntityPacket;
use pocketmine\network\mcpe\protocol\AddItemEntityPacket;
use pocketmine\network\mcpe\protocol\AddItemPacket;
use pocketmine\network\mcpe\protocol\AddPaintingPacket;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\BlockEntityDataPacket;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\BlockPickRequestPacket;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\network\mcpe\protocol\ChunkRadiusUpdatedPacket;
use pocketmine\network\mcpe\protocol\ClientboundMapItemDataPacket;
use pocketmine\network\mcpe\protocol\ClientToServerHandshakePacket;
use pocketmine\network\mcpe\protocol\CommandBlockUpdatePacket;
use pocketmine\network\mcpe\protocol\CommandStepPacket;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\ContainerSetContentPacket;
use pocketmine\network\mcpe\protocol\ContainerSetDataPacket;
use pocketmine\network\mcpe\protocol\ContainerSetSlotPacket;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\network\mcpe\protocol\CraftingEventPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\DisconnectPacket;
use pocketmine\network\mcpe\protocol\DropItemPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\network\mcpe\protocol\ExplodePacket;
use pocketmine\network\mcpe\protocol\FullChunkDataPacket;
use pocketmine\network\mcpe\protocol\HurtArmorPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\InventoryActionPacket;
use pocketmine\network\mcpe\protocol\ItemFrameDropItemPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\MapInfoRequestPacket;
use pocketmine\network\mcpe\protocol\MobArmorEquipmentPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\MoveEntityPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\EntityFallPacket;
use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\PlayStatusPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\RemoveBlockPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\ReplaceItemInSlotPacket;
use pocketmine\network\mcpe\protocol\RequestChunkRadiusPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkDataPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkRequestPacket;
use pocketmine\network\mcpe\protocol\ResourcePackClientResponsePacket;
use pocketmine\network\mcpe\protocol\ResourcePackDataInfoPacket;
use pocketmine\network\mcpe\protocol\ResourcePacksInfoPacket;
use pocketmine\network\mcpe\protocol\RespawnPacket;
use pocketmine\network\mcpe\protocol\RiderJumpPacket;
use pocketmine\network\mcpe\protocol\ServerToClientHandshakePacket;
use pocketmine\network\mcpe\protocol\SetCommandsEnabledPacket;
use pocketmine\network\mcpe\protocol\SetDifficultyPacket;
use pocketmine\network\mcpe\protocol\SetEntityDataPacket;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\network\mcpe\protocol\SetEntityMotionPacket;
use pocketmine\network\mcpe\protocol\SetHealthPacket;
use pocketmine\network\mcpe\protocol\SetPlayerGameTypePacket;
use pocketmine\network\mcpe\protocol\SetSpawnPositionPacket;
use pocketmine\network\mcpe\protocol\SetTimePacket;
use pocketmine\network\mcpe\protocol\SetTitlePacket;
use pocketmine\network\mcpe\protocol\ShowCreditsPacket;
use pocketmine\network\mcpe\protocol\SpawnExperienceOrbPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\StopSoundPacket;
use pocketmine\network\mcpe\protocol\TakeItemEntityPacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\network\mcpe\protocol\UnknownPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\UpdateTradePacket;
use pocketmine\network\mcpe\protocol\UseItemPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\SourceInterface;
use pocketmine\permission\PermissibleBase;
use pocketmine\permission\PermissionAttachment;
use pocketmine\plugin\Plugin;
use pocketmine\tile\ItemFrame;
use pocketmine\tile\Sign;
use pocketmine\tile\Spawnable;
use pocketmine\tile\Tile;
use pocketmine\utils\Binary;
use pocketmine\utils\Config;
use pocketmine\utils\Color;
use pocketmine\utils\Random;
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;
use pocketmine\Player;
use pocketmine\Server;

class main extends PluginBase implements Listener{
	public function onEnable(){
		$this->server = $this->getServer();
		$this->server->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->notice("ShopSystem稼働確認");
		if(!file_exists($this->getDataFolder())){mkdir($this->getDataFolder(), 0744, true);}
		$this->b = new Config($this->getDataFolder()."shop.yml",Config::YAML);
		if($this->getServer()->getPluginManager()->getPlugin("HayaoPVE") != null){
			$this->E = $this->getServer()->getPluginManager()->getPlugin("HayaoPVE");
			$this->getLogger()->info("HayaoPVEを検出しました。");
		}else{
			$this->getLogger()->warning("HayaoPVEが見つかりませんでした");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}

	}

	public function Tap(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$block = $event->getBlock();
		$id = $block->getId();
		$inv = $player->getInventory();
		$var = $block->getX().":".$block->getY().":".$block->getZ().":".$block->getLevel()->getFolderName();
		if($this->b->exists($var)){
			$b = $this->b->getAll();
				$sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
				$id = $b[$var]["id"];
				$money = $b[$var]["money"];
				$itemName = Item::fromString($id)->getName();
				$this->var[$name] = $var;
						$data = [
							'type'    => 'custom_form',
							'title'   => '§aSHOP',
							'content' => [
								[
									"type" => "label",
									"text" => "§e".$itemName."§rを購入しますか?\n一つの値段は§e".$money."円§rです\n\n"
								],
								[
								"type"        => "input",
								"text"        => "買う数",
								"placeholder" => "数字入れてね数字じゃなかったら...!?",
								"default"     => ""
								],
							],
                    	];
				$this->createWindow($player, $data, 68008);
		}
	}

	public function onReceive(DataPacketReceiveEvent $event){
		$pk = $event->getPacket();
		$p = $event->getPlayer();
		$name = $p->getName();
		if($pk instanceof ModalFormResponsePacket) {
			$id = $pk->formId;
			$data = $pk->formData;
			$result = json_decode($data);
			if($data == "null\n"){
			}else{
				if($id === 68008){
						if(isset($this->var[$name])){
							if($result[1] === ""){
								$p->sendMessage("§c入力されていません");
							}elseif(!is_numeric($result[1])){
								$p->sendMessage("§c数字にして下さい");
							}else{
								$result[1] = intval($result[1]);
								$var = $this->var[$name];
								$b = $this->b->getAll();
								$money = $b[$var]["money"];
								$this->count[$name] = $result[1];
								$itemMoney = $money * $this->count[$name];
								$playerMoney = $this->getMoney($name);
								if($playerMoney >= $itemMoney){
									$jdata = [
										'type'    => 'modal',
										'title'   => "確認",
										'content' => "本当に購入しますか?\nあなたの所持金: ".$playerMoney."\n商品の総価格: ".$itemMoney."",
										'button1' => "購入する",
										'button2' => "やめる",
									];
									$this->createWindow($p, $jdata, 68009);
								}else{
									$p->sendMessage("§cお金が足りません");
								}
							}
						}else{
							$p->sendMessage("§cデータに不具合がありました1");
						}
				}elseif($id === 68009){
					if($data == "true\n"){
						if(isset($this->var[$name]) and isset($this->count[$name])){
							$var = $this->var[$name];
							$b = $this->b->getAll();
							$money = $b[$var]["money"];
							$itemMoney = $money * $this->count[$name];
							$this->removeMoney($name, $itemMoney);
							$item = Item::fromString($b[$var]["id"]);
							$id = $item->getId();
							$meta = $item->getDamage();
							$count = $this->count[$name];
							$i = Item::get($id, $meta, $count);
							$p->getInventory()->addItem($i);
							$p->sendMessage("§a購入しました");
						}else{
							$p->sendMessage("§cデータに不具合がありました");
						}
					}elseif($data == "false\n"){
						$p->sendMessage("§c取り消しました");
					}
				}
			}
		}
	}


    public function onSignChange(SignChangeEvent $event){
		$result = $event->getLine(0);
		if($result == "shop"){
			$player = $event->getPlayer();
			if(!$player->isOp()){
				$player->sendMessage("§c権限がありません");
				return;
                        }
			$block = $event->getBlock();
            $id = $event->getLine(1);
            $money = $event->getLine(2);
            if(is_numeric($money)){
				$var = (Int)$event->getBlock()->getX().":".(Int)$event->getBlock()->getY().":".(Int)$event->getBlock()->getZ().":".$block->getLevel()->getFolderName();
          	    $this->b->set($var, [
					"x" => $block->getX(),
					"y" => $block->getY(),
					"z" => $block->getZ(),
					"level" => $block->getLevel()->getFolderName(),
					"money" => $money,
					"id" => $id
				]);
                $this->b->save();
				$player->sendMessage("§bShopを作りました!");
                        $X = (Int)$event->getBlock()->getX();
                        $Y = (Int)$event->getBlock()->getY();
                        $Z = (Int)$event->getBlock()->getZ();
                        $w = $block->getLevel();
                        $y = $Y - 1;
                $itemName = Item::fromString($id)->getName();
				$event->setLine(0, "§b[SHOP]"); // TAG
				$event->setLine(1, "§e名前: ".$itemName);
				$event->setLine(2, "§eItemId: ".$id); 
				$event->setLine(3, "§a値段: ".$money."円"); 
			}else{
				$player->sendMessage("§c正しく入力してください");
			}
		}
	}

	public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
        $block = $event->getBlock();
        $x = $block->getX();
        $y = $block->getY();
        $z = $block->getZ();
        $x = intval($x);
        $y = intval($y);
        $z = intval($z);
        $world = $block->getLevel()->getName();
        $name = $player->getName(); 
		$var = (Int)$event->getBlock()->getX().":".(Int)$event->getBlock()->getY().":".(Int)$event->getBlock()->getZ().":".$block->getLevel()->getFolderName();
        if($this->b->exists($var)){
            if($player->isOp()){
                $this->b->remove($var);
                $this->b->save();
                $player->sendMessage("§cShopを解体しました");
            }else{
                $name = $player->getName();
                $player->getServer()->broadcastMessage("Shopを§c".$name."§6が破壊しようとしています! : world名§d".$world."");
                $event->setCancelled();
            }
        }
    }

	public function createWindow(Player $player, $data, int $id){
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
		$player->dataPacket($pk);
	}

	public function getMoney($name){
		return $this->E->getMoney($name);
	}

	public function addMoney($name, $plus){
		$this->E->addMoney($name, +$plus);
	}

	public function removeMoney($name, $remove){
		$this->E->removeMoney($name, $remove);
	}
}
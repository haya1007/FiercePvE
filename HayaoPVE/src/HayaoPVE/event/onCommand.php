<?php

namespace HayaoPVE\event;

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
use pocketmine\inventory\ArmorInventory;
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
use pocketmine\item\enchantment\ProtectionEnchantment;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Armor;
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
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\nbt\tag\NoDynamicFieldsTrait;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
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

use HayaoPVE\main;

class onCommand{
	function __construct(main $main){
		$this->main = $main;
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args){
		if($sender instanceof Player){
			$player = $sender->getPlayer();
			$name = $player->getName();
			if($label === "kyouka"){
				$player = $sender->getPlayer();
				$name = $player->getName();
				$item = $player->getInventory()->getItemInhand();
				$tag = $item->getNamedTag();
				if($item->getNamedTagEntry("name") !== null and $item->getNamedTagEntry("status") !== null){
					$itemname = $item->getNamedTagEntry("name")->getValue();
					$status = $item->getNamedTagEntry("status")->getValue();
					$goukei = $status + 1;
					$item->setNamedTagEntry(new IntTag("status", $goukei));
					$item->setNamedTagEntry(new IntTag("kyouka", $item->getNamedTagEntry("status")->getValue() + 1));
					$item->setCustomName($itemname." ATK: ".$goukei);
					$sender->getInventory()->setItemInhand($item);
					$sender->sendMessage("§a >> 強化した!");
				}elseif($item->getNamedTagEntry("name") !== null and $item->getNamedTagEntry("def") !== null){
					$itemname = $item->getNamedTagEntry("name")->getValue();
					$status = $item->getNamedTagEntry("def")->getValue();
					$goukei = $status + 1;
					$item->setNamedTagEntry(new IntTag("def", $goukei));
					$item->setNamedTagEntry(new IntTag("kyouka", $item->getNamedTagEntry("status")->getValue() + 1));
					$item->setCustomName($itemname." DEF: ".$goukei);
					$sender->getInventory()->setItemInhand($item);
					$sender->sendMessage("§a >> 強化した!");			
				}else{
					$sender->sendMessage("§c >> 出来ないや!");				
				}
			}elseif($label === "reset"){
				$sender->getInventory()->clearAll();
				$sender->sendMessage('§7アイテムを全て消去しました');
			}elseif($label === 'id'){
				$hand = $sender->getInventory()->getItemInHand();
				$sender->sendMessage((string) $hand);
			}elseif($label === 'xyz'){
				$x = $sender->getX();
				$y = $sender->getY();
				$z = $sender->getZ();
				$X = round($x, 1);
				$Y = round($y, 1);
				$Z = round($z, 1);
				$sender->sendMessage('§eあなたの座標  §lX:'.$X.' Y:'.$Y.' Z:'.$Z);
			}elseif($label == 'money'){
				$money = $this->main->getMoney($name);
				$sender->sendMessage('§l§b'.$money.'M');
			}elseif($label == 'addmoney'){
				if(count($args) === 0){
					$sender->sendMessage('/addmoney Player名 金額');
					return true;
				}elseif(count($args) === 1){
					$sender->sendMessage('/addmoney Player名 金額');
					return true;
				}
				if(!isset($args[2])){
					$username = strtolower($args[0]);
	      			$player = $sender->getServer()->getPlayer($username);
	      			if(!$player == null){
	      				$pName = $player->getName();
	      				$plus = $args[1];
	      				if($plus == true){
		      				$this->main->addMoney($pName, $plus);
		      				$sender->sendMessage('§a'.$pName.'に'.$plus.'Mを渡しました');
		      				$player->sendMessage('§a権限者の'.$name.'から'.$plus.'M送られました');
		      			}else{
		      				$sender->sendMessage('§c数字にしてください');
		      			}	      				
	      			}else{
	      				$sender->sendMessage('そのプレイヤーはログインしていません');
	      			}
	      		}
			}elseif($label == 'removemoney'){
				if(count($args) === 0){
					$sender->sendMessage('/removemoney Player名 金額');
					return true;
				}elseif(count($args) === 1){
					$sender->sendMessage('/removemoney Player名 金額');
					return true;
				}
				if(!isset($args[2])){
					$username = strtolower($args[0]);
	      			$player = $sender->getServer()->getPlayer($username);
	      			if(!$player == null){
	      				$pName = $player->getName();
	      				$remove = $args[1];
	      				if($remove == true){
		      				$this->main->removeMoney($pName, $remove);
		      				$sender->sendMessage('§a'.$pName.'から'.$remove.'Mをとりました');
		      				$player->sendMessage('§a権限者の'.$name.'から'.$remove.'M引かれました');
		      			}else{
		      				$sender->sendMessage('§c数字にしてください');
		      			}
	      			}else{
	      				$sender->sendMessage('そのプレイヤーはログインしていません');
	      			}
	      		}
	      	}elseif($label == 'setmoney'){
				if(count($args) === 0){
					$sender->sendMessage('/setmoney Player名 金額');
					return true;
				}elseif(count($args) === 1){
					$sender->sendMessage('/setmoney Player名 金額');
					return true;
				}
				if(!isset($args[2])){
					$username = strtolower($args[0]);
	      			$player = $sender->getServer()->getPlayer($username);
	      			if(!$player == null){
	      				$pName = $player->getName();
	      				$set = $args[1];
	      				if($set == true){
		      				$this->main->setMoney($pName, $set);
		      				$sender->sendMessage('§a'.$pName.'を'.$set.'Mに変更しました');
		      				$player->sendMessage('§a権限者の'.$name.'から'.$set.'Mに変更されました');
		      			}else{
		      				$sender->sendMessage('§c数字にしてください');
		      			}
	      			}else{
	      				$sender->sendMessage('そのプレイヤーはログインしていません');
	      			}
	      		}
	      	}elseif($label == 'seemoney'){
				if(count($args) === 0){
					$sender->sendMessage('/seemoney Player名');
					return true;
				}
				if(!isset($args[1])){
					$username = strtolower($args[0]);
	      			$player = $sender->getServer()->getPlayer($username);
	      			if(!$player == null){
	      				$pName = $player->getName();
	      				$money = $this->main->getMoney($pName);
						$sender->sendMessage('§l§b'.$pName.' : '.$money.'M');
					}else{
						$sender->sendMessage('§cそのプレイヤーはログインしてません');
					}
				}
	      	}elseif($label == 'pay'){
				if(count($args) === 0){
					$sender->sendMessage('/pay Player名 金額');
					return true;
				}elseif(count($args) === 1){
					$sender->sendMessage('/pay Player名 金額');
					return true;
				}
				if(!isset($args[2])){
					$username = strtolower($args[0]);
					$have = $this->main->getMoney($name);
	      			$player = $sender->getServer()->getPlayer($username);
	      			$money = $args[1];
	      			if(!$player == null){
	      				$pName = $player->getName();
	      				if(!$money == 0 and $money == true){
	      					if($have > $money){
	      						$player->sendMessage($name.'さんから'.$money.'M渡されました');
	      						$this->main->addMoney($pName, $money);
	      						$sender->sendMessage($pName.'さんに'.$money.'M渡しました');
	      						$this->main->sendremoveMoney($name, $money);
	      					}else{
	      						$sender->sendMessage('所持金が足りません');
	      					}
	      				}else{
	      					$sender->sendMessage('渡すお金を設定してください');
	      				}
	      			}else{
	      				$sender->sendMessage('そのプレイヤーはログインしていません');
	      			}
	      		}
	      	}elseif($label === "relog"){
				$pk = new TransferPacket();
				$pk->address = "mcbe-jp.net";
				$pk->port = 19133;
				$sender->dataPacket($pk);
			}elseif($label === 'hide'){
				if(isset($args[0])){
					$player = $this->main->getServer()->getPlayer($args[0]);
					if(isset($player)){
						$sender->teleport($player);
					}else{
						$sender->sendMessage('§c指定されたプレイヤーが見つかりません');
					}
				}else{
					$gamemode = $sender->getGamemode();
					if($gamemode === 3){
						$sender->setGamemode(1);
					}else{
						$sender->setGamemode(3);
					}
				}
			}elseif($label === "ss"){
				if(!isset($args[0])){
					$sender->sendMessage("/ss check : 個数確認\n/ss save : 強化石保存\n/ss get 個数 : 強化石を引き出す");
				}elseif($args[0] == "check"){
					$count = $this->main->config[$name]->get("count");
					$sender->sendMessage('§b保存されてる個数は'.$count."個です");
				}elseif($args[0] == "save"){
					$inv = $sender->getInventory();
					$count2 = $this->main->config[$name]->get("count");
					$hand = $inv->getItemInHand();
					$id = $hand->getId();
					$meta = $hand->getDamage();
					if($id == 378 and $meta == 0){
						$count = $hand->getCount();
						$stone = Item::get(378, 0, $count);
						$inv->removeItem($stone);
						$this->main->config[$name]->set("count", $count2 + $count);
						$this->main->config[$name]->save();
						$sender->sendMessage("§a強化石を".$count."個保存しました");
					}else{
						$sender->sendMessage("§c強化石を持ってください");
					}
				}elseif($args[0] == "get"){
					if(!isset($args[1])){
						$sender->sendMessage("/ss get 個数 : 強化石を引き出す\n");
					}else{
						$kosuu = $args[1];
						$save = $this->main->config[$name]->get("count");
						if($kosuu > $save){
							$sender->sendMessage("§c保存されてる量以上は引き出せません");
						}else{
							if(is_numeric($kosuu)){
								$this->main->config[$name]->set("count", $save - $kosuu);
								$item = Item::get(378, 0, $kosuu)->setCustomName("強化用素材石");
								if($player->getInventory()->canAddItem($item)){
									$sender->getInventory()->addItem($item);
									$sender->sendMessage("§a強化石を".$kosuu."個引き出しました");
									$this->main->config[$name]->save();
								}else{
									$sender->sendMessage("§l§cインベントリに空きがありません");
								}
							}else{
								$sender->sendMessage("§c数字を入力してね");
							}
						}
					}
				}else{
					$sender->sendMessage("/SS check : 個数確認\n/SS save : 強化石保存\n/SS get 個数 : 強化石を引き出す\n§c/SS getはインベントリに空きが無いと強化石が消える可能性があります");
				}
			}elseif($label == "addExp"){
				if(!isset($args[0])){
					$sender->sendMessage("/addExp [Player名] [量]");
				}elseif(!isset($args[1])){
					$sender->sendMessage("/addExp [Player名] [量]");
				}else{
					$exp = $args[1];
					$name = $args[0];
					$player1 = $this->main->getServer()->getPlayer($name);
					if(isset($player1)){
						$name = $player1->getName();
						$this->main->addExp($name, $exp);
						$sender->sendMessage("§a".$name."に".$exp."EXP与えました");
						$player1->sendMessage("§a権限者に".$exp."EXP渡されました");
					}else{
						$sender->sendMessage("§cそのプレイヤーは存在しません");
					}
				}
			}elseif($label === "setOrb"){
				if(isset($args[0]) && isset($args[1])){
					$player = $this->getServer()->getPlayer($args[0]);
					if(isset($player)){
						if(is_numeric($args[1])){
							$count = $args[1];
							$name = $player->getName();
							$this->main->setOrb($name, $count);
							$this->main->config[$name]->save();
						}else{
							$sender->sendMessage("§l§c数字で入力してください");
						}
					}else{
						$sender->sendMessage("§l§cそのプレイヤーは存在しません");
					}
				}else{
					$sender->sendMessage("§l§c/setOrb [PlayerName] [Count]");
				}
			}elseif($label === "tapxyz"){
				$name = strtolower($sender->getName());
				if(isset($this->main->tap[$name])){
					if($this->main->tap[$name] == "on"){
						$sender->sendMessage("§aTapXYZをoffにしました");
						$this->main->tap[$name] = "off";
					}else{
						$sender->sendMessage("§aTapXYZをonにしました");
						$this->main->tap[$name] = "on";						
					}
				}
			}elseif($label === "warpss"){
				$data = [
				    'type'    => 'form',
				    'title'   => 'Warp',
				    'content' => "どこにワープする?\n",
				    'buttons' => [
				    	['text' => "Lobby"],
				 	    ['text' => "一階層"],
				 	 	['text' => "やめる"]
				    ]
				];
				$this->main->createWindow($sender, $data, 100);
			}elseif($label === "weapon"){
				if(isset($args[0])){
					$player = $this->main->getServer()->getPlayer($args[1]);
					if(isset($player)){
						$item = $player->getInventory()->getItemInhand();
						if($item->getNamedTagEntry("name") !== null){
						}else{
							$sender->sendMessage("§l§cその人の武器は計測できません");
						}
					}
				}
			}elseif($label === "addPoint"){
				if(isset($args[0]) && isset($args[1])){
					$player = $this->main->getServer()->getPlayer($args[0]);
					if(isset($player)){
						if(is_numeric($args[1])){
							if($args[1] > 0){
								$this->main->setStatus($sender->getName(), "point", $args[1]);
								$sender->sendMessage("§a".$player->getName()."のステータスポイントを".$args[1]."にセットしました");
								$player->sendMessage("§a権限者にステータスポイントを".$args[1]."にセットされました");
							}else{
								$sender->sendMessage("§l§c0より大きい数字で入力してください");
							}
						}else{
							$sender->sendMessage("§l§c数字で入力してください");
						}
					}else{
						$sender->sendMessage("§l§cそのプレイヤーは存在しません");
					}
				}else{
					$sender->sendMessage("§l§c/addPoint [Player] [Count]");
				}
			}elseif($label === "addOrb"){
				if(isset($args[0]) && isset($args[1])){
					$player = $this->main->getServer()->getPlayer($args[0]);
					if(isset($player)){
						if(is_numeric($args[1])){
							if($args[1] > 0){
								$this->main->setOrb($player->getName(), $args[1]);
								$sender->sendMessage("§a".$player->getName()."の宝石石を".$args[1]."にセットしました");
								$player->sendMessage("§a権限者に宝石石を".$args[1]."にセットされました");
							}else{
								$sender->sendMessage("§l§c0より大きい数字で入力してください");
							}
						}else{
							$sender->sendMessage("§l§c数字で入力してください");
						}
					}else{
						$sender->sendMessage("§l§cそのプレイヤーは存在しません");
					}
				}else{
					$sender->sendMessage("§l§c/addPoint [Player] [Count]");
				}
			}
		}
	}
}
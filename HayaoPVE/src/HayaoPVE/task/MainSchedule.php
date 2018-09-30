<?php

namespace HayaoPVE\task;

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

class MainSchedule extends Task{

	function __construct(PluginBase $owner){
		$this->owner = $owner;
	}

	function onRun(int $currentTick){
		foreach($this->owner->getServer()->getOnlinePlayers() as $player){
			$name = $player->getName();
			$job = $this->owner->config[$name]->get("job");
			if($job !== ""){
				$data = $this->owner->getStatus($name);
				$lv = $this->owner->getLevel($name);
				$atk = $this->owner->atk[$name];
	 			$def = $this->owner->def[$name];
				$i = $player->getInventory()->getItemInhand();
				if($i->getNamedTagEntry("job") !== null){
					$ijob = $i->getNamedTagEntry("job")->getValue();
				}
				$tag = $i->getNamedTag();
				$iname = $i->getCustomName();
				$damageTable = [
					Item::WOODEN_SWORD => 4,
					Item::GOLD_SWORD => 4,
					Item::STONE_SWORD => 5,
					Item::IRON_SWORD => 6,
					Item::DIAMOND_SWORD => 7,

					Item::WOODEN_AXE => 3,
					Item::GOLD_AXE => 3,
					Item::STONE_AXE => 3,
					Item::IRON_AXE => 5,
					Item::DIAMOND_AXE => 6,

					Item::WOODEN_PICKAXE => 2,
					Item::GOLD_PICKAXE => 2,
					Item::STONE_PICKAXE => 3,
					Item::IRON_PICKAXE => 4,
					Item::DIAMOND_PICKAXE => 5,

					Item::WOODEN_SHOVEL => 1,
					Item::GOLD_SHOVEL => 1,
					Item::STONE_SHOVEL => 2,
					Item::IRON_SHOVEL => 3,
					Item::DIAMOND_SHOVEL => 4,

					Item::BOW => 2,

					Item::STICK => 0,
				];
				$plusdamage = $damageTable[$i->getId()] ?? 1;
				if($player->hasEffect(Effect::STRENGTH)){
					$stren =  1 + ($player->getEffect(Effect::STRENGTH)->getEffectLevel() * 0.3);
				}else{
					$stren = 1;
				}
				if($i->getid() === 268 or $i->getid() === 272 or $i->getid() === 267 or $i->getid() === 283 or $i->getid() === 276 or $i->getid() === 261 or $i->getid() === 286 or $i->getid() === 388 or $i->getid() === 352 or $i->getId() === 264 or $i->getid() === 256 or $i->getId() === 338 or $i->getId() === 281 or $i->getId() === 280 or $i->getId() === 275 or $i->getId() === 278){

					if($i->getNamedTagEntry("status") !== null){
						if(isset($ijob)){
							if($job === "剣士"){
								if($ijob === "剣士"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;								
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "マジシャン"){
								if($ijob === "マジシャン"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "アーチャー"){
								if($ijob === "アーチャー"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "アコライト"){
								if($ijob === "アコライト"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "ルーク"){
								if($ijob === "剣士" || $ijob === "ルーク"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "ピジョップ"){
								if($ijob === "アコライト" || $ijob === "ピジョップ"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "ウォーロック"){
								if($ijob === "マジシャン" || $ijob === "ウォーロック"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "マーセナリー"){
								if($ijob === "アーチャー" || $ijob === "マーセナリー"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "ウォリアー"){
								if($ijob === "剣士" || $ijob === "ルーク" || $ijob === "ウォリアー"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "モンク"){
								if($ijob === "アコライト" || $ijob === "ピジョップ" || $ijob === "モンク"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "ネクロマンサー"){
								if($ijob === "マジシャン" || $ijob === "ウォーロック" || $ijob === "ネクロマンサー"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "ブレイカー"){
								if($ijob === "アーチャー" || $ijob === "マーセナリー" || $ijob === "ブレイカー"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "デスペラード"){
								if($ijob === "剣士" || $ijob === "ルーク" || $ijob === "ウォリアー" || $ijob === "デスペラード"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "ヴァルキリー"){
								if($ijob === "アコライト" || $ijob === "ピジョップ" || $ijob === "モンク" || $ijob === "ヴァルキリー"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "魔道剣士"){
								if($ijob === "マジシャン" || $ijob === "ウォーロック" || $ijob === "ネクロマンサー" || $ijob === "魔道剣士"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}elseif($job === "アサシン"){
								if($ijob === "アーチャー" || $ijob === "マーセナリー" || $ijob === "ブレイカー" || $ijob === "アサシン"){
									$atk = $i->getNamedTagEntry("status")->getValue();
									$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
								}else{
									$aatk = (0 + $plusdamage + $data["str"]) * $stren;
								}
							}
							if($ijob === "All"){
								$atk = $i->getNamedTagEntry("status")->getValue();
								$aatk = ($atk + $plusdamage + $data["str"]) * $stren;
							}
							$this->owner->atk[$name] = $aatk;
						}else{
							$aatk = (0 + $plusdamage + $data["str"]) * $stren;
							$this->owner->atk[$name] = $aatk;
						}
					}else{
						$aatk = (0 + $plusdamage + $data["str"]) * $stren;
					}
					$this->owner->atk[$name] = $aatk;
				}else{
					$aatk = (0 + $plusdamage + $data["str"]) * $stren;
					$this->owner->atk[$name] = $aatk;
				}
				if($i->getid() !== 298 and $i->getid() !== 299 and $i->getid() !== 300 and $i->getid() !== 301 and $i->getid() !== 444 and $i->getid() !== 306 and $i->getid() !== 307 and $i->getid() !== 308 and $i->getid() !== 309 and $i->getid() !== 310 and $i->getid() !== 311 and $i->getid() !== 312 and $i->getid() !== 313){
					if($aatk > 0){
						if($atk !== $aatk){
							$this->owner->atk[$name] = $aatk;
						}
					}else{
						$this->owner->atk[$name] = (1 + $data["str"]) * $stren;
					}
				}else{
					$this->owner->atk[$name] = (1 + $data["str"]) * $stren;
				}
				$a0 = $player->getArmorInventory()->getHelmet();
				$a1 = $player->getArmorInventory()->getChestplate();
				$a2 = $player->getArmorInventory()->getLeggings();
				$a3 = $player->getArmorInventory()->getBoots();
	       		$a0->setDamage(0);
	            $a1->setDamage(0);
	        	$a2->setDamage(0);
	      	   	$a3->setDamage(0);
	      	    $player->getArmorInventory()->setHelmet($a0);
	            $player->getArmorInventory()->setChestplate($a1);
	            $player->getArmorInventory()->setLeggings($a2);
	          	$player->getArmorInventory()->setBoots($a3);
				$def = $this->owner->def[$name];
	 			$a0n = $a0->getCustomName();
	 			$a1n = $a1->getCustomName();
	 			$a2n = $a2->getCustomName();
				$a3n = $a3->getCustomName();
				if($a0->getNamedTagEntry("def") !== null){
					$def0 = intval($a0->getNamedTagEntry("def")->getValue());
				}else{
					$def0 = 0;
				}
				if($a1->getNamedTagEntry("def") !== null){
					$def1 = intval($a1->getNamedTagEntry("def")->getValue());
				}else{
					$def1 = 0;
				}
				if($a2->getNamedTagEntry("def") !== null){
					$def2 = intval($a2->getNamedTagEntry("def")->getValue());
				}else{
					$def2 = 0;
				}
				if($a3->getNamedTagEntry("def") !== null){
					$def3 = intval($a3->getNamedTagEntry("def")->getValue());
				}else{
					$def3 = 0;
				}
				$adef = $def0 + $def1 + $def2 + $def3 + $data["vit"];
				if($adef > 0){
					if($adef !== $def){
						if($a0->getNamedTagEntry("def") === null and $a1->getNamedTagEntry("def") === null and $a2->getNamedTagEntry("def") === null and $a3->getNamedTagEntry("def") === null){
							$this->owner->def[$name] = $data["vit"];
						}else{
							$this->owner->def[$name] = $def0 + $def1 + $def2 + $def3 + $data["vit"];
						}
					}
				}else{
					$this->owner->def[$name] = 0;
				}
				$contents = $player->getInventory()->getContents();
				if(isset($contents[17])){
					$id = $contents[17]->getId();
					$meta = $contents[17]->getDamage();
					$custom_name = $contents[17]->getCustomName();
				}

				$hel = $this->owner->getStatus($name);
				$hel = $hel["hel"];
				$max = 20 + $hel;
				$player->setMaxHealth($max);

				/*if($a0->getNamedTagEntry("mp") !== null){
					$mp0 = intval($a0->getNamedTagEntry("mp")->getValue());
				}else{
					$mp0 = 0;
				}
				if($a1->getNamedTagEntry("mp") !== null){
					$mp1 = intval($a1->getNamedTagEntry("mp")->getValue());
				}else{
					$mp1 = 0;
				}
				if($a2->getNamedTagEntry("mp") !== null){
					$mp2 = intval($a2->getNamedTagEntry("mp")->getValue());
				}else{
					$mp2 = 0;
				}
				if($a3->getNamedTagEntry("mp") !== null){
					$mp3 = intval($a3->getNamedTagEntry("mp")->getValue());
				}else{
					$mp3 = 0;
				}


				$plus = $this->owner->config[$player->getName()]->get("mp");
				if($job === "マジシャン" || $job === "アコライト"){
					$mp = 100;
				}else{
					$mp = 50;
				}
				$max = $mp + $data["int"] + $plus + $mp0 + $mp1 + $mp2 + $mp3;
				$xp = $player->getXpLevel();
				if($max - 1 >= $xp){
					$player->setXpLevel($xp + 1);
				}*/

				$player->sendPopup("§l§cATK: ".$this->owner->atk[$name]." §bDEF: ".$this->owner->def[$name]);

				$level = $this->owner->getLevel($name);
				$exp = $this->owner->getExp($name);
				$job = $this->owner->getJob($name);
				$orb = $this->owner->getOrb($name);
				$money = $this->owner->getMoney($name);
				$up = $this->owner->getLevelUpExpectedExperience($level, $exp);
				$point = $this->owner->getStatus($name)["point"];
				if($i->getNamedTagEntry("special") !== null){
					$special = $i->getNamedTagEntry("special")->getValue();
				}else{
					$special = "なし";
				}
				if($this->owner->config[$name]->get("guild") === ""){
					$guild = "加入していません";
				}else{
					$guild = $this->owner->config[$name]->get("guild");
				}
				$hhh = '                                                                          ';
				$eol = '§r'."\n";
				$color = '§2';
				$space = $eol.'§l'.$color;
				$player->sendTip(
					$space.$hhh.'§d  >== §e'.$name.' §d==<'.
					$space.$hhh.'Level: Lv.'.$level.
					$space.$hhh.'経験値: '.$exp.'E'.
					$space.$hhh.'レベルアップまで: '.$up.'E'.
					$space.$hhh.'所持金: '.$money.'M'.
					$space.$hhh.'特殊能力: '.$special.
					$space.$hhh.'Guild: '.$guild.
					$space.$hhh."Job: ".$job.
					$space.$hhh."宝石玉: ".$orb."個".
					$space.$hhh."StatusPoint: ".$point."ポイント".
					$space.$hhh."時間: ".date("Y")."年".date("n")."月".date("d")."日".
					$space.$hhh."      ".date("l")."  ".date("A")." ".date("g")."時 : ".date("i")."分 : ".date("s")."秒".
					$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol
				);
				/*$player->sendPopup("Popup");
				$player->sendTip("Tip");
				$player->addActionBarMessage("ActionBar");*/
				#$this->owner->ChangeTag($player);
				/*foreach($this->owner->getServer()->getDefaultLevel()->getEntities() as $entity){
					if($entity instanceof Player){
						if($this->owner->config[$entity->geName()]->get("start") <= 10){
							$entity->setDataFlag(0, 16, true);
						}
					}
				}*/
			}
		}
	}
}
<?php

namespace HayaoPVE\mob;

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
use HayaoPVE\window\rule;
use HayaoPVE\window\weapon;
use HayaoPVE\window\sell;
use HayaoPVE\window\shop;

use HayaoPVE\npc\nkyouka;
use HayaoPVE\npc\nsell;
use HayaoPVE\npc\nquest;
use HayaoPVE\npc\nhelper;
use HayaoPVE\npc\nsakaya;
use HayaoPVE\npc\nshop;

use HayaoPVE\mob\MobDeath;
use HayaoPVE\mob\MobDamage;
use HayaoPVE\mob\PlayerDamage;
use HayaoPVE\mob\MobATK;
use HayaoPVE\mob\fMobSpawn;
use HayaoPVE\mob\MobSkill;
use HayaoPVE\mob\MobAttack;

use HayaoPVE\task\MainSchedule;
use HayaoPVE\task\MobSpawn;
use HayaoPVE\task\MobMove;
use HayaoPVE\task\sound;
use HayaoPVE\task\EntityDelete;
use HayaoPVE\task\close;
use HayaoPVE\task\firedamage;
use HayaoPVE\task\JumpFinish;
use HayaoPVE\task\FloatDelete;
use HayaoPVE\task\Shutdown;
use HayaoPVE\task\save;
use HayaoPVE\task\fly;
use HayaoPVE\task\fly2;
use HayaoPVE\task\EntityRemove;

use HayaoPVE\event\onLogin;
use HayaoPVE\event\onJoin;
use HayaoPVE\event\onQuit;
use HayaoPVE\event\onTap;
use HayaoPVE\event\onReceive;
use HayaoPVE\event\onChat;
use HayaoPVE\event\onEat;
use HayaoPVE\event\onDamage;
use HayaoPVE\event\onCommand;

class MobSkill{
	public $main;

	public function __construct(main $main){
		$this->main = $main;
	}

	public function MobMove_Light($x, $y, $z){
		$rand = mt_rand(1, 10);
		if($rand === 1){
			$x = $x + 2;
			$z = $z + 2;
			$this->main->MobLight($x, $y, $z);
		}elseif($rand === 2){
			$x = $x + 3;
			$z = $z + 6;
			$this->main->MobLight($x, $y, $z);			
		}elseif($rand === 3){
			$x = $x + 1;
			$z = $z + 4;
			$this->main->MobLight($x, $y, $z);			
		}elseif($rand === 4){
			$x = $x - 3;
			$z = $z + 4;
			$this->main->MobLight($x, $y, $z);			
		}elseif($rand === 5){
			$x = $x - 6;
			$z = $z + 1;
			$this->main->MobLight($x, $y, $z);			
		}elseif($rand === 6){
			$x = $x - 3;
			$z = $z - 4;
			$this->main->MobLight($x, $y, $z);				
		}elseif($rand === 7){
			$x = $x - 6;
			$z = $z - 2;
			$this->main->MobLight($x, $y, $z);			
		}elseif($rand === 8){
			$x = $x + 3;
			$z = $z - 4;
			$this->main->MobLight($x, $y, $z);			
		}elseif($rand === 9){
			$x = $x + 4;
			$z = $z - 5;
			$this->main->MobLight($x, $y, $z);			
		}elseif($rand === 10){
			$x = $x - 7;
			$z = $z - 3;
			$this->main->MobLight($x, $y, $z);			
		}
	}

	public function MobMove_DefCrash($damage, $distance, $type, $pos, $level){
		$pk = new AddEntityPacket();
		$pk->type = $type;
		$pk->entityRuntimeId = mt_rand(100000, 10000000);
		$pk->position = $pos;
		$pk->metadata = [];
		$pk2 = new LevelSoundEventPacket;
		$pk2->sound = LevelSoundEventPacket::SOUND_EXPLODE;
		$pk2->position = $pk->position;
		foreach($level->getPlayers() as $players){
			$name = $players->getName();
			$def = $this->main->def[$name];
			$damage = $damage - $def/3;
			if($damage < 1){
				$damage = 1;
			}
			$players->dataPacket($pk);
 			if($players->distanceSquared($pos) <= pow($distance,2)){
			$players->dataPacket($pk2);
				$ev = new EntityDamageEvent($players, EntityDamageEvent::CAUSE_CUSTOM, $damage);
				$players->attack($ev);//ダメージ
				if(!$ev->isCancelled()){
					$motion = (new Vector3(0, 4, 0))->normalize();//ノックバック
					$players->setmotion($motion);
				}
			}
		}
	}

	public function MobMove_Crash($damage, $distance, $type, $pos, $level){
		$pk = new AddEntityPacket();
		$pk->type = $type;
		$pk->entityRuntimeId = mt_rand(100000, 10000000);
		$pk->position = $pos;
		$pk->metadata = [];
		$pk2 = new LevelSoundEventPacket;
		$pk2->sound = LevelSoundEventPacket::SOUND_EXPLODE;
		$pk2->position = $pk->position;
		foreach($level->getPlayers() as $players){
			$name = $players->getName();
			$def = $this->main->def[$name];
			$players->dataPacket($pk);
 			if($players->distanceSquared($pos) <= pow($distance,2)){
			$players->dataPacket($pk2);
				$ev = new EntityDamageEvent($players, EntityDamageEvent::CAUSE_CUSTOM, $damage);
				$players->attack($ev);//ダメージ
				if(!$ev->isCancelled()){
					$motion = (new Vector3(0, 4, 0))->normalize();//ノックバック
					$players->setmotion($motion);
				}
			}
		}
	}

	/*public function MobMove_Stop($player, $time, $level){
		$entity->setDataFlag(0, 16, true);
	}*/

	public function MobMove_RemoveEffect($pos, $level, $distance){
		foreach($level->getPlayers() as $players){
 			if($players->distanceSquared($pos) <= pow($distance,2)){
 				$players->removeAllEffects();
 				$players->sendMessage("§l§c全てのエフェクトを消された!!");
			}
		}
	}

	public function MobMove_Effect($effect, $effectlevel, $effecttime, $distance, $pos, $level){
		$pk2 = new LevelSoundEventPacket;
		$pk2->sound = LevelSoundEventPacket::SOUND_SPLASH;
		$pk2->position = $pos;
		foreach($level->getPlayers() as $players){
			$players->dataPacket($pk2);
 			if($players->distanceSquared($pos) <= pow($distance,2)){
 				if($effectlevel === null){
 					$effectlevel = 1;
 				}
				$players->addEffect(new EffectInstance(Effect::getEffect($effect), $effecttime * 20, $effectlevel, false));
			}
		}
	}

	public function MobMove_Fire($firetime, $distance, $pos, $level){
		$pk2 = new LevelSoundEventPacket;
		$pk2->sound = LevelSoundEventPacket::SOUND_FIRE;
		$pk2->position = $pos;
		foreach($level->getPlayers() as $players){
			$players->dataPacket($pk2);
 			if($players->distanceSquared($pos) <= pow($distance,2)){
				$players->setOnFire($firetime * 20);
			}
		}
	}

	public function MobMove_Jump($eid, $damage, $plusy, $level, $finishtime, $target, $tnt){
		$pk = new MovePlayerPacket();
		$pk->entityRuntimeId = $eid;
		$pk->position = new Vector3($this->main->entity[$eid]["x"], $this->main->entity[$eid]["y"] + $plusy * 15, $this->main->entity[$eid]["z"]);
		$pk->pitch = $this->main->entity[$eid]["pitch"];
		$pk->yaw = $this->main->entity[$eid]["yaw"];
		$pk->headYaw = $this->main->entity[$eid]["yaw"];
		$this->main->entity[$eid]["y"] = $this->main->entity[$eid]["y"] + $plusy;
		$this->main->entity[$eid]["move"] = 0;
		#$this->main->entity[$eid]["target"] = "";
		foreach($this->main->getServer()->getOnlinePlayers() as $players){
			$players->dataPacket($pk);
		}
		$this->main->getScheduler()->scheduleDelayedTask(new JumpFinish($this->main, $eid, $damage, $level, $plusy, $target, $tnt), $finishtime * 20);
	}

	public function MobMove_Heal($eid, $amount, $level, $pos, $distance){
		$this->main->entity[$eid]["hp"] = $this->main->entity[$eid]["hp"] + $amount;
		if($this->main->entity[$eid]["hp"] >= $this->main->entity[$eid]["maxhp"]){
			$this->main->entity[$eid]["hp"] = $this->main->entity[$eid]["maxhp"];
		}
		foreach($level->getPlayers() as $players){
 			if($players->distanceSquared($pos) <= pow($distance,2)){
			}
		}
	}

	public function MobMove_Explode($x, $y, $z, $distance, $pos, $level, $damage){
		foreach($level->getPlayers() as $players){
			$this->main->getScheduler()->scheduleDelayedTask(new Sound($this->main, $pos ,$level, $players, 1), 0.5 * 20);
			$this->main->getScheduler()->scheduleDelayedTask(new Sound($this->main, $pos ,$level, $players, 1), 0.5 * 20);
			$this->main->getScheduler()->scheduleDelayedTask(new Sound($this->main, $pos ,$level, $players, 1), 0.5 * 20);
 			if($players->distanceSquared($pos) <= pow($distance,2)){
 				$name = $players->getName();
				$def = $this->main->def[$name];
 				$damage = $damage - $def/5;
				if($damage < 1){
					$damage = 1;
				}
				$ev = new EntityDamageEvent($players, EntityDamageEvent::CAUSE_CUSTOM, $damage);
				$players->attack($ev);//ダメージ
				if(!$ev->isCancelled()){
					if($players->getX() - $x >= 0){
						$motionx = 3;
					}else{
						$motionx = -3;
					}
					if($players->getZ() - $z >= 0){
						$motionz = 3;
					}else{
						$motionz = -3;
					}
					$motion = (new Vector3($motionx, 2, $motionz))->normalize();//ノックバック
					$players->setmotion($motion);
				}
			}
		}
	}
}
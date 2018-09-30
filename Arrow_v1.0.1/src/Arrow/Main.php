<?php

namespace Arrow;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandExecutor;
use pocketmine\scheduler\Task;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\PressurePlate;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\Attribute;
use pocketmine\entity\Effect;
use pocketmine\entity\FallingSand;
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
use pocketmine\entity\Item as DroppedItem;
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
use pocketmine\level\ChunkLoader;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\level\sound\LaunchSound;
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
use pocketmine\network\mcpe\protocol\MoveEntityAbsolutePacket;
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
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;
use pocketmine\Player;
use pocketmine\Server;

class Main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!file_exists($this->getDataFolder())){
			mkdir($this->getDataFolder(), 0744, true);
		}
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array());
		if(!$this->config->exists("RoopSpeed")){
			$this->config->set("RoopSpeed", 1);
			$this->config->save();
		}
		if(!$this->config->exists("Damage")){
			$this->config->set("Damage", 4);
			$this->config->save();
		}
		if(!$this->config->exists("Speed")){
			$this->config->set("Speed", 1);
			$this->config->save();
		}
		if(!$this->config->exists("Type")){
			$this->config->set("Type", 80);
			$this->config->save();
		}
		$roopspeed = $this->config->get("RoopSpeed");
		$this->roopspeed = $roopspeed;//ここの数値を高くすれば軽くなる
		$this->HumanAI = $this->getServer()->getPluginManager()->getPlugin("HayaoPVE");
	}

	public function onShootBow(EntityShootBowEvent $event){
		$player = $event->getEntity();
		if($player instanceof Player){
			$name = $player->getName();
				//$damage = $this->config->get("Damage");
				$damage = $this->HumanAI->atk[$name];
				$speed = $this->config->get("Speed") * $this->roopspeed;
				$yaw = $player->getYaw();
				$pitch = $player->getPitch();
				$level = $player->getLevel();
				$x = $player->x;
				$y = $player->y + 1.35;
				$z = $player->z;
				$pk = new AddEntityPacket();
				$eid = mt_rand(100000, 10000000);
				$pk->entityUniqueId = $eid;
				$pk->entityRuntimeId = $eid;
				$pk->type = $this->config->get("Type");
				$pk->position = new Vector3($x, $y, $z);
				$pk->yaw = $yaw;
				$pk->pitch = $pitch;
				@$flags |= 0 << Entity::DATA_FLAG_INVISIBLE;
				@$flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
				@$flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
				@$flags |= 0 << Entity::DATA_FLAG_IMMOBILE;
				@$flags |= 0 << Entity::DATA_FLAG_AFFECTED_BY_GRAVITY;
				$pk->metadata = [
					Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
					Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, ""],
					Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
	 				Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 1],//大きさ
				];
				foreach($level->getPlayers() as $players){
					$players->dataPacket($pk);
				}
				$this->getScheduler()->scheduleDelayedTask(new ArrowMove($this, $eid, $x, $y, $z, $yaw, $pitch, $damage, $speed, $player), $this->roopspeed);//ループ
				$pk = new LevelSoundEventPacket;
				$pk->sound = LevelSoundEventPacket::SOUND_BOW;
				$pk->position = new Vector3($player->x, $player->y, $player->z);
				foreach($level->getPlayers() as $p){
					$p->dataPacket($pk);
				}
				$player->getInventory()->removeItem(Item::get(262,0,1));
				$event->setCancelled();
		}
	}

	public function Shoot($player, $type, $damage, $speed, $size, $x, $y, $z, $yaw, $pitch, $level){
		$pk = new AddEntityPacket();
		$eid = mt_rand(100000, 10000000);
		$pk->entityUniqueId = $eid;
		$pk->entityRuntimeId = $eid;
		$pk->type = $type;
		$pk->position = new Vector3($x, $y, $z);
		$pk->yaw = $yaw;
		$pk->pitch = $pitch;
		@$flags |= 0 << Entity::DATA_FLAG_INVISIBLE;
		@$flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
		@$flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
		@$flags |= 0 << Entity::DATA_FLAG_IMMOBILE;
		@$flags |= 0 << Entity::DATA_FLAG_AFFECTED_BY_GRAVITY;
		$pk->metadata = [
			Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
			Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, ""],
			Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
	 		Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, $size],//大きさ
			  	];
		foreach($level->getPlayers() as $players){
			$players->dataPacket($pk);
		}
		$this->getScheduler()->scheduleDelayedTask(new ArrowMove($this, $eid, $x, $y, $z, $yaw, $pitch, $damage, $speed / 10, $player), $this->roopspeed);//ループ
		/*$pk = new LevelSoundEventPacket;
		$pk->sound = LevelSoundEventPacket::SOUND_BOW;
		$pk->position = new Vector3($player->x, $player->y, $player->z);
		foreach($level->getPlayers() as $p){
			$p->dataPacket($pk);
		}*/
	}

	public function BlockShoot($player, $id, $damage, $speed, $size, $x, $y, $z, $yaw, $pitch, $level){
		$pk = new AddEntityPacket();
		$eid = mt_rand(100000, 10000000);
		$pk->entityUniqueId = $eid;
		$pk->entityRuntimeId = $eid;
		$pk->type = 66;
		$pk->position = new Vector3($x, $y, $z);
		$pk->yaw = $yaw;
		$pk->pitch = $pitch;
		@$flags |= 0 << Entity::DATA_FLAG_INVISIBLE;
		@$flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
		@$flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
		@$flags |= 0 << Entity::DATA_FLAG_IMMOBILE;
		$pk->metadata = [
			Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
			Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, ""],
			Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
	 		Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, $size],//大きさ
			FallingSand::DATA_VARIANT => [Entity::DATA_TYPE_INT, $id | 0 << 8],
			  	];
		foreach($level->getPlayers() as $players){
			$players->dataPacket($pk);
		}
		$this->getScheduler()->scheduleDelayedTask(new ArrowMove($this, $eid, $x, $y, $z, $yaw, $pitch, $damage, $speed, $player), $this->roopspeed);//ループ
		/*$pk = new LevelSoundEventPacket;
		$pk->sound = LevelSoundEventPacket::SOUND_BOW;
		$pk->position = new Vector3($player->x, $player->y, $player->z);
		foreach($level->getPlayers() as $p){
			$p->dataPacket($pk);
		}*/
	}
}

class ArrowMove extends Task{

	function __construct(PluginBase $owner, $eid, $x, $y, $z, $yaw, $pitch, $damage, $speed, $player){
		#parent::__construct($owner);
		$this->owner = $owner;
		$this->eid = $eid;
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		$this->yaw = $yaw;
		$this->pitch = $pitch;
		$this->damage = $damage;
		$this->speed = $speed;
		$this->player = $player;
	}

	function onRun(int $currentTick){
		$player = $this->player;
		$level = $player->getLevel();
		$eid = $this->eid;
		$x = $this->x;
		$y = $this->y;
		$z = $this->z;
		$pos = new Vector3($x, $y, $z);
		$bid = $level->getBlock($pos)->getId();
		if($bid === null){
			$bid = 1;
		}else{
			$bid = $level->getBlock($pos)->getId();
		}
		if($bid === 0 and $y <= 256 and $y > 0 and $player instanceof Player){
			$yaw = $this->yaw;
			$pitch = $this->pitch;
			$damage = $this->damage;
			$speed = $this->speed;
			$plusy = -sin(deg2rad($pitch));
			$plusxz = cos(deg2rad($pitch));
			$plusx = -$plusxz * sin(deg2rad($yaw));
			$plusz = $plusxz * cos(deg2rad($yaw));
			$x = $x + $plusx * $speed;
			$y = $y + $plusy * $speed;
			$z = $z + $plusz * $speed;
			$pk = new MoveEntityAbsolutePacket();
			$pk->entityRuntimeId = $eid;
			$pk->position = new Vector3($x, $y-0.1, $z);
			/*$pk->yaw = $yaw;
			$pk->headYaw = $yaw;
			$pk->pitch = $pitch;*/
			$pk->xRot = $pitch;
			$pk->yRot = $yaw;
			$pk->zRot = $yaw;
			foreach($level->getPlayers() as $players){
				$players->dataPacket($pk);
			}
			$hit = 0;
			if(isset($this->owner->HumanAI->eid)){
				foreach($this->owner->HumanAI->eid as $humanid){
					$humanx = $this->owner->HumanAI->entity[$humanid]["x"];
					$humany = $this->owner->HumanAI->entity[$humanid]["y"];
					$humanz = $this->owner->HumanAI->entity[$humanid]["z"];
					$humansize = $this->owner->HumanAI->entity[$humanid]["size"];
					$epos = new Vector3($humanx, $humany, $humanz);
					if($pk->position->distanceSquared($epos) <= pow($humansize*1.5,2)){
					//if($pk->position->distance($epos) <= 2){
						//$player->sendPopup('§aHIT!!');
						$this->owner->HumanAI->mob_MobDamage->MobDamage($player, $humanid, $damage, 0);
						$hit++;
					}
				}
			}
			if($hit > 0){
				$pk = new RemoveEntityPacket();
				$pk->entityUniqueId = $eid;
				foreach($this->owner->getServer()->getOnlinePlayers() as $players){
					$players->dataPacket($pk);
				}
			}else{
				$this->owner->getScheduler()->scheduleDelayedTask(new ArrowMove($this->owner, $eid, $x, $y, $z, $yaw, $pitch, $damage, $speed, $player), $this->owner->roopspeed);//ループ
			}
		}else{
			$pk = new RemoveEntityPacket();
			$pk->entityUniqueId = $this->eid;
			foreach($this->owner->getServer()->getOnlinePlayers() as $players){
				$players->dataPacket($pk);
			}
		}
	}
}
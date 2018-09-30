<?php

namespace HumanPets;

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
use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\entity\Attribute;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
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
use pocketmine\entity\projectile\Arrow;
use pocketmine\entity\projectile\Snowball;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\ItemFrameDropItemEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityCombustByEntityEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\entity\EntityExplodeEvent;
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
use pocketmine\item\enchantment\EnchantmentInstance;
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


class Main extends PluginBase implements Listener{

	public $drops = array();

	public function onEnable(){
		$this->roopspeed = 1;
		$this->HumanAI = $this->getServer()->getPluginManager()->getPlugin("HayaoPVE");
		$this->Arrow = $this->getServer()->getPluginManager()->getPlugin("Arrow");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getScheduler()->scheduleRepeatingTask(new PetMove($this), $this->roopspeed);
	}

	public function onLogin(PlayerLoginEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$this->pet[$name] = 0;
		$this->petid[$name] = 0;
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		if(isset($this->eid)){
			foreach($this->eid as $eid){
			$petname = $this->Petdata[$eid]["name"];
			$x = $this->Petdata[$eid]["x"];
			$y = $this->Petdata[$eid]["y"];
			$z = $this->Petdata[$eid]["z"];
			$yaw = $this->Petdata[$eid]["yaw"];
			$pitch = $this->Petdata[$eid]["pitch"];
			$itemid = $this->Petdata[$eid]["item"];
			$pk = new AddPlayerPacket();
			$pk->entityRuntimeId = $eid;
			$pk->uuid = UUID::fromRandom();
			$pk->username = $petname;
			$pk->position = new Vector3($x, $y, $z);
			$pk->speedX = 0;
		       	$pk->speedY = 0;
		       	$pk->speedZ = 0;
		       	$pk->yaw = $yaw;
		       	$pk->pitch = $pitch;
	        	$pk->item = Item::get($itemid,0,1);
			@$flags |= 0 << Entity::DATA_FLAG_INVISIBLE;
			@$flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
			@$flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
			@$flags |= 0 << Entity::DATA_FLAG_IMMOBILE;
		       	$pk->metadata = [
				Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
					Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $petname],
				  	Entity::DATA_FLAG_NO_AI => [Entity::DATA_TYPE_BYTE, 1],
				  	Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
					Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, $this->Petdata[$eid]["size"]],//大きさ
				  	];
		 	$skin = $this->Petdata[$eid]["skin"];
			$this->getServer()->updatePlayerListData($pk->uuid, $pk->entityRuntimeId, $petname, $skin, $this->Petdata[$eid]["xbox"], $this->getServer()->getOnlinePlayers());
			$player->dataPacket($pk);
			$pk2 = new MobEquipmentPacket();
			$pk2->entityRuntimeId = $eid;
			$pk2->item = Item::get(intval($itemid),0,1);
			$pk2->inventorySlot = 0;
			$pk2->hotbarSlot = 0;
			$player->dataPacket($pk2);//Item
			}
		}
	}

	public function onTouch(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$block = $event->getBlock();
		$pet = $this->pet[$name];
		$i = $player->getInventory()->getIteminhand();
		$item = $player->getInventory()->getIteminhand();
		$itemname = $item->getCustomName();
		$action = $event->getAction();
		if($action === 2 or $action === 3){
			if($i->getNamedTagEntry("petname") !== null and $i->getNamedTagEntry("petatk") !== null and $i->getNamedTagEntry("petspeed") !== null and $i->getNamedTagEntry("petskinid") !== null and $i->getNamedTagEntry("petskindata") !== null and $i->getNamedTagEntry("petcapedata") !== null and $i->getNamedTagEntry("petgeometryname") !== null and $i->getNamedTagEntry("petgeometrydata") !== null){
						$data = [
						    'type'    => 'form',
						    'title'   => 'Pet',
						    'content' => "ペットのオプションです",
						    'buttons' => [
			 			    ['text' => "召喚する(既に召喚してたら消えます)"],
			 			    ['text' => "ペットのステータス"],
			 		     	['text' => "やめる"]
						    ]
					   ];
					   $this->createWindow($player, $data, 8000);
			}
		}
	}

	public function onReceive(DataPacketReceiveEvent $event){
		$pk = $event->getPacket();
		$p = $event->getPlayer();
		$player = $event->getPlayer();
		$name = $p->getName();
		if($pk instanceof ModalFormResponsePacket) {
			$i = $player->getInventory()->getIteminhand();
			$item = $player->getInventory()->getIteminhand();
			$itemname = $item->getCustomName();
			$pet = $this->pet[$name];
			$id = $pk->formId;
			$data = $pk->formData;
			$result = json_decode($data);
			if($data == "null\n"){
			}else{
				if($id === 8000){
					if($data == 0){
						if($pet === 0){
							$itemid = 0;
							$eid = mt_rand(100000, 10000000);
							$this->pet[$name] = 1;
							$this->petid[$name] = $eid;
							$petname = "[".$name."] \n".$i->getNamedTagEntry("petname")->getValue()."";
							$pk = new AddPlayerPacket();
							$pk->entityRuntimeId = $eid;
							$pk->username = $petname;
							$pk->uuid = UUID::fromRandom();
							$pk->position = new Vector3($p->x, $p->y, $p->z);
							$pk->yaw = 0;
							$pk->pitch = 0;
							$pk->item = Item::get($itemid);
							@$flags |= 0 << Entity::DATA_FLAG_INVISIBLE;
							@$flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
							@$flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
							@$flags |= 0 << Entity::DATA_FLAG_IMMOBILE;
							$pk->metadata = [
								Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
								Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $petname],
								Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
				 				Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0.5],//大きさ
								  	];
							$player->dataPacket($pk);
							$geometryJsonEncoded = base64_decode($i->getNamedTagEntry("petgeometrydata")->getValue());
							if($geometryJsonEncoded !== ""){
								$geometryJsonEncoded = \json_encode(\json_decode($geometryJsonEncoded));
							}
					 		$skin = new Skin(base64_decode($i->getNamedTagEntry("petskinid")->getValue()), base64_decode($i->getNamedTagEntry("petskindata")->getValue()), base64_decode($i->getNamedTagEntry("petcapedata")->getValue()), base64_decode($i->getNamedTagEntry("petgeometryname")->getValue()), $geometryJsonEncoded);
					 		$xbox = mt_rand(10000, 100000000);
							$this->getServer()->updatePlayerListData($pk->uuid, $pk->entityRuntimeId, $petname, $skin, $xbox, $this->getServer()->getOnlinePlayers());
							foreach($this->getServer()->getOnlinePlayers() as $player){
								$player->dataPacket($pk);
								$pk2 = new MobEquipmentPacket();
								$pk2->entityRuntimeId = $eid;
								$pk2->item = Item::get(intval($itemid),0,1);
								$pk2->inventorySlot = 0;
								$pk2->hotbarSlot = 0;
								$player->dataPacket($pk2);//Item
							}
							$this->Petdata[$eid]["name"] = $petname;
							$this->Petdata[$eid]["playername"] = $name;
							$this->Petdata[$eid]["item"] = $itemid;
							$this->Petdata[$eid]["maxhp"] = 20;
							$this->Petdata[$eid]["hp"] = 20;
							$this->Petdata[$eid]["atk"] = $i->getNamedTagEntry("petatk")->getValue();
							$this->Petdata[$eid]["atkrange"] = $i->getNamedTagEntry("petrange")->getValue();
							$this->Petdata[$eid]["atktime"] = 0;
							$this->Petdata[$eid]["reatk"] = floor($i->getNamedTagEntry("petatkspeed")->getValue() / $this->roopspeed);
							$this->Petdata[$eid]["speed"] = $i->getNamedTagEntry("petspeed")->getValue() / floor(20 / $this->roopspeed);
							$this->Petdata[$eid]["x"] = $p->x;
							$this->Petdata[$eid]["y"] = $p->y;
							$this->Petdata[$eid]["z"] = $p->z;
							$this->Petdata[$eid]["yaw"] = 0;
							$this->Petdata[$eid]["pitch"] = 0;
							$this->Petdata[$eid]["move"] = 0;
							$this->Petdata[$eid]["uuid"] = $pk->uuid;
							$this->Petdata[$eid]["xbox"] = $xbox;
							$this->Petdata[$eid]["skin"] = $skin;
							$this->Petdata[$eid]["size"] = 0.5;
							$this->Petdata[$eid]["playerdistance"] = 3;
							$this->Petdata[$eid]["monsterdistance"] = $i->getNamedTagEntry("petrange")->getValue();
							if($i->getNamedTagEntry("petbtype") !== null){
								$this->Petdata[$eid]["btype"] = $i->getNamedTagEntry("petbtype")->getValue();
							}
							if($i->getNamedTagEntry("petbspeed") !== null){
								$this->Petdata[$eid]["bspeed"] = $i->getNamedTagEntry("petbspeed")->getValue();
							}
							if($i->getNamedTagEntry("petbsize") !== null){
								$this->Petdata[$eid]["bsize"] = $i->getNamedTagEntry("petbsize")->getValue();
							}
							$x = $this->Petdata[$eid]["x"];
							$y = $this->Petdata[$eid]["y"];
							$z = $this->Petdata[$eid]["z"];
							$yaw = $this->Petdata[$eid]["yaw"];
							$pitch = $this->Petdata[$eid]["pitch"];
							$this->Petdata[$eid]["target"] = $this->Petdata[$eid]["playername"];
							$target = $this->Petdata[$eid]["target"];
							$this->eid[$eid] = $eid;
						}elseif($pet === 1){
							$this->pet[$name] = 0;
							$petid = $this->petid[$name];
							$pk = new RemoveEntityPacket();
							$pk->entityUniqueId = $petid;
							foreach($this->getServer()->getOnlinePlayers() as $player){
								$player->dataPacket($pk);
							}
							$this->getServer()->removePlayerListData($this->Petdata[$petid]["uuid"], $this->getServer()->getOnlinePlayers());
							unset($this->Petdata[$petid]);
							unset($this->eid[$petid]);
						}
					}elseif($data == 1){
						$name = $i->getNamedTagEntry("petname")->getValue();
						$atk = $i->getNamedTagEntry("petatk")->getValue();
						$speed = $i->getNamedTagEntry("petspeed")->getValue();
						$range = $i->getNamedTagEntry("petrange")->getValue();
						$atkspeed = $i->getNamedTagEntry("petatkspeed")->getValue();
						$data = [
						    'type'    => 'form',
						    'title'   => "§l§ePetStatus",
						    'content' => "\n\n§aName: ".$name."\n§cATK: ".$atk."\n§bSPEED: ".$speed."\n§6RANGE: ".$range."\n§aAttackSpeed: ".$atkspeed."tick\n\n",
					  	 	'buttons' => [
		 		     			['text' => "もどる"]
					   		]
					   ];
					   $this->createWindow($p, $data, 8001);
					}
				}
			}
		}
	}

	public function createWindow(Player $player, $data, int $id){
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
		$player->dataPacket($pk);
	}
}

class PetMove extends Task{

	function __construct(PluginBase $owner){
		$this->owner = $owner;
	}

	function onRun(int $currentTick){
		if(isset($this->owner->eid)){
			foreach($this->owner->eid as $eid){
				if(isset($this->owner->Petdata[$eid])){
					$target = $this->owner->Petdata[$eid]["target"];
					$playername = $this->owner->Petdata[$eid]["playername"];
					$targetplayer = $this->owner->getServer()->getPlayer($target);
					$player = $this->owner->getServer()->getPlayer($playername);
					if(!$player instanceof Player){
						$pk = new RemoveEntityPacket();
						$pk->entityUniqueId = $eid;
						foreach($this->owner->getServer()->getOnlinePlayers() as $players){
							$players->dataPacket($pk);
						}
						$this->owner->getServer()->removePlayerListData($this->owner->Petdata[$eid]["uuid"], $this->owner->getServer()->getOnlinePlayers());
						unset($this->owner->Petdata[$eid]);
						unset($this->owner->eid[$eid]);
					}else{
						$x = $this->owner->Petdata[$eid]["x"];
						$y = $this->owner->Petdata[$eid]["y"];
						$z = $this->owner->Petdata[$eid]["z"];
						$yaw = $this->owner->Petdata[$eid]["yaw"];
						$pitch = $this->owner->Petdata[$eid]["pitch"];
						$pos = new Vector3($x,$y + 1.62,$z);
						$speed = $this->owner->Petdata[$eid]["speed"];
						$lastattack = $this->owner->HumanAI->getLastAttackPacketHuman($playername);
						if(isset($this->owner->HumanAI->entity[$lastattack])){
							$px = $this->owner->HumanAI->entity[$lastattack]["x"];
							$py = $this->owner->HumanAI->entity[$lastattack]["y"];
							$pz = $this->owner->HumanAI->entity[$lastattack]["z"];
							$targetpos = new Vector3($px, $py, $pz);
							$level = $player->getLevel();
						}else{
							$px = $player->x;
							$py = $player->y;
							$pz = $player->z;
							$targetpos = new Vector3($px, $py, $pz);
							$level = $player->getLevel();
							$this->owner->Petdata[$eid]["target"] = $this->owner->Petdata[$eid]["playername"];
						}
						$epx = $px - $x;
						$epy = $py - $y;
						$epz = $pz - $z;
						$playerdistance = $this->owner->Petdata[$eid]["playerdistance"];
						$monsterdistance = $this->owner->Petdata[$eid]["monsterdistance"];
						if(($target === $playername and $targetpos->distance($pos) <= $playerdistance) or ($target !== $playername and $targetpos->distance($pos) <= $monsterdistance)){
							if($px > $x){
								$x = $x + 0;
							}else{
								$x = $x - 0;
							}
							if($pz > $z){
								$z = $z + 0;
							}else{
								$z = $z - 0;
							}
						}else{
							if($px > $x){
								$x = $x + $speed;
							}else{
								$x = $x - $speed;
							}
							if($pz > $z){
								$z = $z + $speed;
							}else{
								$z = $z - $speed;
							}
						}
						$yy = $y - 1;
						$yaw = rad2deg(atan2($epz, $epx)) - 90;
						if($yaw < 0){
							$yaw = $yaw + 360;
						}
						$blockid = $level->getBlock(new Vector3($x, $y, $z))->getID();
						$blockid2 = $level->getBlock(new Vector3($x, $yy, $z))->getID();
						$blockid3 = $level->getBlock(new Vector3($x, $y+1, $z))->getID();
						if($blockid !== 0 and $blockid !== 6 and $blockid !== 8 and $blockid !== 9 and $blockid !== 10 and $blockid !== 11 and $blockid !== 27 and $blockid !== 28 and $blockid !== 30 and $blockid !== 31 and $blockid !== 32 and $blockid !== 37 and $blockid !== 38 and $blockid !== 39 and $blockid !== 40 and $blockid !== 50 and $blockid !== 51 and $blockid !== 55 and $blockid !== 59 and $blockid !== 63 and $blockid !== 68 and $blockid !== 70 and $blockid !== 72 and $blockid !== 75 and $blockid !== 76 and $blockid !== 78 and $blockid !== 83 and $blockid !== 90 and $blockid !== 104 and $blockid !== 105 and $blockid !== 106 and $blockid !== 115 and $blockid !== 119 and $blockid !== 126 and $blockid !== 132 and $blockid !== 141 and $blockid !== 142 and $blockid !== 147 and $blockid !== 148 and $blockid !== 171 and $blockid !== 175  and $blockid !== 199 and $blockid !== 244){
							$y++;
						}elseif($blockid2 === 0 or $blockid2 === 6 or $blockid2 === 8 or $blockid2 === 9 or $blockid2 === 10 or $blockid2 === 11 or $blockid2 === 27 or $blockid2 === 28 or $blockid2 === 30 or $blockid2 === 31 or $blockid2 === 32 or $blockid2 === 37 or $blockid2 === 38 or $blockid2 === 39 or $blockid2 === 40 or $blockid2 === 50 or $blockid2 === 51 or $blockid2 === 55 or $blockid2 === 59 or $blockid2 === 63 or $blockid2 === 68 or $blockid2 === 70 or $blockid2 === 72 or $blockid2 === 75 or $blockid2 === 76 or $blockid2 === 78 or $blockid2 === 83 or $blockid2 === 90 or $blockid2 === 104 or $blockid2 === 105 or $blockid2 === 106 or $blockid2 === 115 or $blockid2 === 119 or $blockid2 === 126 or $blockid2 === 132 or $blockid2 === 141 or $blockid2 === 142 or $blockid2 === 147 or $blockid2 === 148 or $blockid2 === 171 or $blockid2 === 175 or $blockid2 === 199 or $blockid2 === 244){
							$y--;
						}elseif($blockid3 !== 0 and $blockid3 !== 8 and $blockid3 !== 9 and $blockid3 !== 10 and $blockid3 !== 11){
							$y++;
						}
						if($player->distance($pos) >= 32){
							$targetplayer = $this->owner->getServer()->getPlayer($playername);
							$x = $targetplayer->getX();
							$y = $targetplayer->getY();
							$z = $targetplayer->getZ();
							$this->owner->Petdata[$eid]["target"] = $this->owner->Petdata[$eid]["playername"];
							unset($this->owner->HumanAI->lastattack[$playername]);
						}
						$pk = new MovePlayerPacket();
						$pk->entityRuntimeId = $eid;
						$pk->position = new Vector3($x, $y + 1.62, $z);
						$pk->pitch = $pitch;
						$pk->yaw = $yaw;
						$pk->headYaw = $yaw;
						foreach($this->owner->getServer()->getOnlinePlayers() as $players){
							$players->dataPacket($pk);
						}
						$this->owner->Petdata[$eid]["x"] = $x;
						$this->owner->Petdata[$eid]["y"] = $y;
						$this->owner->Petdata[$eid]["z"] = $z;
						$this->owner->Petdata[$eid]["yaw"] = $yaw;
						$this->owner->Petdata[$eid]["pitch"] = $pitch;
						$target = $this->owner->Petdata[$eid]["target"];
						$lastattack = $this->owner->HumanAI->getLastAttackPacketHuman($playername);
						if(isset($this->owner->HumanAI->entity[$lastattack])){
							$this->owner->Petdata[$eid]["target"] = $lastattack;
						}else{
							$this->owner->Petdata[$eid]["target"] = $target;
						}
						$this->owner->Petdata[$eid]["atktime"] = $this->owner->Petdata[$eid]["atktime"] + $this->owner->roopspeed;
						$atktime = $this->owner->Petdata[$eid]["atktime"];
						$reatk = $this->owner->Petdata[$eid]["reatk"];
						$atkrange = $this->owner->Petdata[$eid]["atkrange"];
						$playername = $this->owner->Petdata[$eid]["playername"];
						if($target !== $playername and $atktime >= $reatk and $targetpos->distance($pos) <= $atkrange){
							$atk = $this->owner->Petdata[$eid]["atk"];
							if(isset($this->owner->Petdata[$eid]["btype"]) and isset($this->owner->Petdata[$eid]["bspeed"]) and isset($this->owner->Petdata[$eid]["bsize"])){
								$btype = $this->owner->Petdata[$eid]["btype"];
								$bspeed = $this->owner->Petdata[$eid]["bspeed"];
								$bsize = $this->owner->Petdata[$eid]["bsize"];
								$this->owner->Arrow->Shoot($player, $btype, $atk, $bspeed, $bsize, $x, $y + 1.1, $z, $yaw, $pitch, $level);
							}else{
								$pk = new EntityEventPacket();
								$pk->entityRuntimeId = $eid;
								$pk->event = 9;
								foreach($this->owner->getServer()->getOnlinePlayers() as $players){
									$players->dataPacket($pk);
								}
								$this->owner->HumanAI->mob_MobDamage->MobDamage($player, $target, $atk, 0);
							}
							$this->owner->Petdata[$eid]["atktime"] = 0;
							$pk = new EntityEventPacket();
							$pk->entityRuntimeId = $eid;
							$pk->event = 4;
							foreach($this->owner->getServer()->getOnlinePlayers() as $players){
								$players->dataPacket($pk);
							}
						}
					}
				}
			}
		}
	}
}

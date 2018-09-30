<?php

namespace HayaoPVE;

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
use pocketmine\inventory\ChestInventory;
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
use pocketmine\item\Chest;
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

use HayaoPVE\window\rule;
use HayaoPVE\window\weapon;
use HayaoPVE\window\sell;
use HayaoPVE\window\shop;
use HayaoPVE\window\sakaya;
use HayaoPVE\window\option;

use HayaoPVE\window\gatya\gatya_main;
use HayaoPVE\window\gatya\monday;
use HayaoPVE\window\gatya\tuesday;
use HayaoPVE\window\gatya\wednesday;
use HayaoPVE\window\gatya\thuesday;
use HayaoPVE\window\gatya\friday;
use HayaoPVE\window\gatya\saturday;
use HayaoPVE\window\gatya\sunday;

use HayaoPVE\npc\nkyouka;
use HayaoPVE\npc\nsell;
use HayaoPVE\npc\nquest;
use HayaoPVE\npc\nhelper;
use HayaoPVE\npc\nsakaya;
use HayaoPVE\npc\nshop;
use HayaoPVE\npc\ngatya;

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
use HayaoPVE\task\addMP;

use HayaoPVE\event\onLogin;
use HayaoPVE\event\onJoin;
use HayaoPVE\event\onQuit;
use HayaoPVE\event\onTap;
use HayaoPVE\event\onReceive;
use HayaoPVE\event\onChat;
use HayaoPVE\event\onEat;
use HayaoPVE\event\onDamage;
use HayaoPVE\event\onCommand;

use RuinPray\ui\UI;
use RuinPray\ui\elements\StepSlider;
use RuinPray\ui\elements\Dropdown;
use RuinPray\ui\elements\Toggle;

class main extends PluginBase implements Listener{
	private $main;

	public function _construct(string $pg){
		$this->main = $pg;
	}

	public function onEnable(){
		$this->roopspeed = 2;
		$this->k = mt_rand(1000, 10000000);
		$this->t = mt_rand(1000, 10000000);
		$this->o = mt_rand(1000, 10000000);
		$this->d = mt_rand(1000, 10000000);
		$this->b = mt_rand(1000, 10000000);
		$this->h = mt_rand(1000, 10000000);
		$this->q = mt_rand(1000, 10000000);
		$this->g = mt_rand(1000, 10000000);

		$this->text = 1000000000;
		$this->server = $this->getServer();
		$this->server->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->notice("PvEメインシステム　製作者:hayao");
		if(!file_exists($this->getDataFolder())){mkdir($this->getDataFolder(), 0744, true);}
		if(!file_exists($this->getDataFolder()."gatya/")){mkdir($this->getDataFolder()."gatya/", 0744, true);}
		if(!file_exists("/var/lib/pufferd/servers/3ad417db-b532-4abc-bb93-0680c45b0f02/fiercebattle/data2/")){mkdir("/var/lib/pufferd/servers/3ad417db-b532-4abc-bb93-0680c45b0f02/fiercebattle/data2/", 0744, true);}
		$this->config['entity'] = new Config($this->getDataFolder().'Entity.json', Config::JSON, array());
		$this->entity = $this->config['entity']->getAll();
		$this->item_config = new Config($this->getDataFolder() . "item.json", Config::JSON, array());
		$this->citem = $this->item_config->getAll();

		$this->item_monday = new Config($this->getDataFolder() . "gatya/monday.json", Config::JSON, array());
		$this->item_gatya_monday = $this->item_monday->getAll();

		$this->item_tuesday = new Config($this->getDataFolder() . 'gatya/tuesday.json', Config::JSON, array());
		$this->item_gatya_tuesday = $this->item_tuesday->getAll();

		$this->item_wednesday = new Config($this->getDataFolder() . "gatya/wednesday.json", Config::JSON, array());
		$this->item_gatya_wednesday = $this->item_wednesday->getAll();

		$this->item_thuesday = new Config($this->getDataFolder() . 'gatya/thuesday.json', Config::JSON, array());
		$this->item_gatya_thuesday = $this->item_thuesday->getAll();

		$this->item_friday = new Config($this->getDataFolder() . 'gatya/friday.json', Config::JSON, array());
		$this->item_gatya_friday = $this->item_friday->getAll();

		$this->item_saturday = new Config($this->getDataFolder() . "gatya/saturday.json", Config::JSON, array());
		$this->item_gatya_saturday = $this->item_saturday->getAll();

		$this->item_sunday = new Config($this->getDataFolder() . "gatya/sunday.json", Config::JSON, array());
		$this->item_gatya_sunday = $this->item_sunday->getAll();

		$this->gatya_config = new Config($this->getDataFolder() . "gatya/gatya.yml", Config::YAML, array());

		$this->c = new Config($this->getDataFolder() . "config.yml", Config::YAML, array());
		$this->tag = new Config($this->getDataFolder() . "tag.yml", Config::YAML, array());
		$this->setting = new Config($this->getDataFolder() . "setting.yml", Config::YAML, array(
			"config" => "on"
		));
		if($this->getServer()->getPluginManager()->getPlugin("otu") != null){
			$this->otu = $this->getServer()->getPluginManager()->getPlugin("otu");
		}
		if($this->getServer()->getPluginManager()->getPlugin("PvEGuild") != null){
			$this->guild = $this->getServer()->getPluginManager()->getPlugin("PvEGuild");
		}
		if($this->getServer()->getPluginManager()->getPlugin("QuestPlugin") != null){
			$this->questplugin = $this->getServer()->getPluginManager()->getPlugin("QuestPlugin");
		}
		$this->level['world'] = $this->server->getLevelByName('world');
		foreach ($this->item_config->getAll() as $key => $data){
			$rarity = $this->RarityMark($data["rarity"]);
			$item = Item::get($data["id"], $data["meta"], 1)->setCustomName($data["cname"]."\n§6レア度§r: ".$rarity);
			if(isset($data["colorR"]) and isset($data["colorG"]) and isset($data["colorB"])){
				$color = new Color($data["colorR"], $data["colorG"], $data["colorB"]);
				$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
				$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
			}
			$item->setNamedTagEntry(new StringTag("name", $data["name"]));
			$item->setNamedTagEntry(new IntTag("kyouka", 0));
			if(isset($data["atk"])){
				$item->setNamedTagEntry(new IntTag("status", $data["atk"]));
			}
			if(isset($data["def"])){
				$item->setNamedTagEntry(new IntTag("def", $data["def"]));
			}
			if(isset($data["sell"])){
				$item->setNamedTagEntry(new IntTag("sell", $data["sell"]));
			}
			if(isset($data["special"])){
				$item->setNamedTagEntry(new StringTag("special", $data["special"]));
			}
			if(isset($data["sinka"])){
				$item->setNamedTagEntry(new StringTag("sinka", $data["sinka"]));
			}
			if(isset($data["sozainame1"])){
				$item->setNamedTagEntry(new StringTag("sozainame1", $data["sozainame1"]));
			}
			if(isset($data["sozai1"])){
				$item->setNamedTagEntry(new StringTag("sozai1", $data["sozai1"]));
			}
			if(isset($data["kosuu1"])){
				$item->setNamedTagEntry(new StringTag("kosuu1", $data["kosuu1"]));
			}
			if(isset($data["job"])){
				$item->setNamedTagEntry(new StringTag("job", $data["job"]));				
			}
			if(isset($data["mp"])){
				$item->setNamedTagEntry(new IntTag("mp", $data["mp"]));				
			}
			if(isset($data["type"])){
				$item->setNamedTagEntry(new StringTag("type", $data["type"]));								
			}
			if(isset($data["rarity"])){
				$item->setNamedTagEntry(new IntTag("rarity", $data["rarity"]));
			}
			if(isset($data["petname"])){
				$item->setNamedTagEntry(new StringTag("petname", $data["petname"]));
			}
			if(isset($data["petatk"])){
				$item->setNamedTagEntry(new IntTag("petatk", $data["petatk"]));
			}
			if(isset($data["petspeed"])){
				$item->setNamedTagEntry(new IntTag("petspeed", $data["petspeed"]));
			}
			if(isset($data["petrange"])){
				$item->setNamedTagEntry(new IntTag("petrange", $data["petrange"]));
			}
			if(isset($data["petatkspeed"])){
				$item->setNamedTagEntry(new IntTag("petatkspeed", $data["petatkspeed"]));
			}
			if(isset($data["petbtype"])){
				$item->setNamedTagEntry(new IntTag("petbtype", $data["petbtype"]));
			}
			if(isset($data["petbspeed"])){
				$item->setNamedTagEntry(new IntTag("petbspeed", $data["petbspeed"]));
			}
			if(isset($data["petbsize"])){
				$item->setNamedTagEntry(new IntTag("petbsize", $data["petbsize"]));
			}
			if(isset($data["petskinid"]) and isset($data["petskindata"]) and isset($data["petcapedata"]) and isset($data["petgeometryname"]) and isset($data["petgeometrydata"])){
				$item->setNamedTagEntry(new StringTag("petskinid", $data["petskinid"]));
				$item->setNamedTagEntry(new StringTag("petskindata", $data["petskindata"]));
				$item->setNamedTagEntry(new StringTag("petcapedata", $data["petcapedata"]));
				$item->setNamedTagEntry(new StringTag("petgeometryname", $data["petgeometryname"]));
				$item->setNamedTagEntry(new StringTag("petgeometrydata", $data["petgeometrydata"]));
			}
			Item::addCreativeItem($item);
			#var_dump($item);
		}

		$this->item = new PvEItem($this);
		$this->item->onEnable();

		$this->window_rule = new rule($this);
		$this->window_weapon = new weapon($this);
		$this->window_sell = new sell($this);
		$this->window_shop = new shop($this);
		$this->window_sakaya = new sakaya($this);
		$this->window_option = new option($this);

		$this->gatya_main = new gatya_main($this);
		$this->gatya_monday = new monday($this);
		$this->gatya_tuesday = new tuesday($this);
		$this->gatya_wednesday = new wednesday($this);
		$this->gatya_thuesday = new thuesday($this);
		$this->gatya_friday = new friday($this);
		$this->gatya_saturday = new saturday($this);
		$this->gatya_sunday = new sunday($this);

		$this->npc_kyouka = new nkyouka($this);
		$this->npc_sell = new nsell($this);
		$this->npc_quest = new nquest($this);
		$this->npc_helper = new nhelper($this);
		$this->npc_sakaya = new nsakaya($this);
		$this->npc_shop = new nshop($this);
		$this->npc_gatya = new ngatya($this);

		$this->mob_MobDeath = new MobDeath($this);
		$this->mob_MobDamage = new MobDamage($this);
		$this->mob_PlayerDamage = new PlayerDamage($this);
		$this->mob_MobATK = new MobATK($this);
		$this->mob_MobSpawn = new fMobSpawn($this);
		$this->skill = new MobSkill($this);
		$this->mob_MobAttack = new MobAttack($this);

		$this->event_onLogin = new onLogin($this);
		$this->event_onJoin = new onJoin($this);
		$this->event_onCommand = new onCommand($this);
		$this->event_onQuit = new onQuit($this);
		$this->event_onTap = new onTap($this);
		$this->event_onReceive = new onReceive($this);
		$this->event_onChat = new onChat($this);
		$this->event_onEat = new onEat($this);
		$this->event_onDamage = new onDamage($this);

		foreach ($this->config['entity']->getAll() as $key => $data){
			$cname = $data["name"];
			$this->$cname = 0;
		}

		$respawntime = 30;
		$this->getScheduler()->scheduleRepeatingTask(new MobSpawn($this), $respawntime);
		$this->getScheduler()->scheduleRepeatingTask(new MobMove($this), $this->roopspeed);
		$this->getScheduler()->scheduleRepeatingTask(new MainSchedule($this), 3);
		$this->getScheduler()->scheduleRepeatingTask(new addMP($this), 20);
    }

	public function onDisable(){
		foreach($this->config as $data) $data->save();
	}

	public function onPlayerLogin(PlayerLoginEvent $event){
		$this->event_onLogin->onLogin($event);
	}

    public function onPlayerJoin(PlayerJoinEvent $event){
    	$this->event_onJoin->onJoin($event);
    }

    public function Quit(PlayerQuitEvent $event){
    	$this->event_onQuit->onQuit($event);
    }

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		$this->event_onCommand->onCommand($sender, $command, $label, $args);
		return true;
	}

	public function onHit(ProjectileHitEvent $event){
		$event->getEntity()->kill();
	}

	public function onTap(PlayerInteractEvent $event){
		$this->event_onTap->onTap($event);
	}

	public function onReceive(DataPacketReceiveEvent $event){
		$this->event_onReceive->onReceive($event);
	}

	public function Chat(PlayerCommandPreprocessEvent $event){//hack対策
		$this->event_onChat->onChat($event);
	}

	public function eat(PlayerItemConsumeEvent $event){
		$this->event_onEat->onEat($event);
	}

	public function Move(PlayerMoveEvent $event){
		$p = $event->getPlayer();
		$y = $p->getY();
		if($y <= 0.1){
			$this->death($p, "");
		}
	}

	public function onDeath(PlayerDeathEvent $event){
		$event->setKeepInventory(true);
	}

	public function onDamage(EntityDamageEvent $event){
		$this->event_onDamage->onDamage($event);
	}

	public function MobClose($eid){
		if(isset($this->entity[$eid])){
			$cname = $this->entity[$eid]["name"];
			$this->$cname = $this->$cname - 1;
			$pk = new EntityEventPacket();
			$pk->entityRuntimeId = $eid;
			$pk->event = 3;
			$pk2 = new RemoveEntityPacket();
			$pk2->entityUniqueId = $eid;
			$this->getServer()->removePlayerListData($this->entity[$eid]["uuid"], $this->getServer()->getOnlinePlayers());
			unset($this->entity[$eid]);
			unset($this->eid[$eid]);
			foreach($this->getServer()->getOnlinePlayers() as $players){
				$players->dataPacket($pk);
				$players->dataPacket($pk2);
			}
		}
	}

	public function isPacketHuman($eid){
		if(isset($this->entity[$eid])){
			return true;
		}else{
			return false;
		}
	}

	public function getPacketHumanName($eid){
		if(isset($this->entity[$eid])){
			return $this->entity[$eid]["name"];
		}else{
			return false;
		}
	}

	public function getLastAttackPacketHuman($name){
		if(isset($this->lastattack[$name])){
			return $this->lastattack[$name];
		}else{
			return false;
		}
	}

	public function Light($eid){
		$lightid = mt_rand(100000,1000000);
		$pk = new AddEntityPacket();
		$pk->entityRuntimeId = $lightid;
		$pk->uuid = UUID::fromRandom();
		$pk->type = 93;
		$pk->yaw = 0;
		$pk->pitch = 0;
		$pk->position = new Vector3($this->entity[$eid]["x"], $this->entity[$eid]["y"], $this->entity[$eid]["z"]);
		$server = $this->getServer();
		$server->broadcastPacket($server->getOnlinePlayers(), $pk);
	}

	public function MobLight($x, $y, $z){
		$eid = mt_rand(100000,1000000);
		$pk = new AddEntityPacket();
		$pk->entityRuntimeId = $eid;
		$pk->uuid = UUID::fromRandom();
		$pk->type = 93;
		$pk->yaw = 0;
		$pk->pitch = 0;
		$pk->position = new Vector3($x, $y, $z);
		$server = $this->getServer();
		$server->broadcastPacket($server->getOnlinePlayers(), $pk);
	}

	public function BossBar(){
		foreach($this->getServer()->getOnlinePlayers() as $player){
			$name = $player->getName();
			if(isset($this->lastattack[$name])){
				$lastatk = $this->lastattack[$name];
				if(isset($this->entity[$lastatk])){
					if(isset($this->entity[$lastatk]["hp"])){
						$percentage = $this->entity[$lastatk]["hp"] / $this->entity[$lastatk]["maxhp"] * 100;
						$upk = new UpdateAttributesPacket();
						$upk->entries[] = new BossBarValues(1, 600, max(1, min([$percentage, 100])) / 100 * 600, 'minecraft:health');
						$upk->entityRuntimeId = $lastatk;
						$player->DataPacket($upk);
						$pk = new BossEventPacket();
						$pk->bossEid = $lastatk;
						$pk->eventType = BossEventPacket::TYPE_SHOW;
						$pk->healthPercent = $percentage / 100;
						$pk->title = "";
						$pk->color = 1;
						$pk->overlay = 0;
						$pk->playerEid = 0;
						$player->DataPacket($pk);
						foreach($this->eid as $eid){
							if($eid !== $lastatk){
								$pk = new BossEventPacket();
								$pk->bossEid = $eid;
								$pk->eventType = BossEventPacket::TYPE_HIDE;
								$player->DataPacket($pk);
							}
						}
					}
				}
			}
		}
	}

	public function ShowNPC($player){
		$this->npc_kyouka->onKyouka($player);
		$this->npc_sell->onSell($player);
		$this->npc_quest->onQuest($player);
		$this->npc_helper->onHelper($player);
		$this->npc_sakaya->onSakaya($player);
		$this->npc_shop->onShop($player);
		$this->npc_gatya->onGatya($player);
	}

	public function FloatDamage($player, $eid, $damage, $color){
		if(isset($this->entity[$eid])){
			$x = $this->entity[$eid]["x"];
			$y = $this->entity[$eid]["y"];
			$z = $this->entity[$eid]["z"];
			$move = $this->entity[$eid]["move"];
			if(!isset($this->entity[$eid]["plusY"])){
				$plusY = 0;
			}else{
				$plusY = $this->entity[$eid]["plusY"];
			}
			if(isset($color)){
				$name = "§l".$color."".$damage."";
			}else{
				$name = "§l".$damage."";
			}
			#$name = "§l".$color."".$damage."";
			$eid = mt_rand(10000000,100000000000);
			$pk = new AddPlayerPacket();
			$pk->entityRuntimeId = $eid;
			$pk->username = $name;
			$pk->uuid = UUID::fromRandom();
			$pkx = $x - 1 + mt_rand(1,20) / 10;
			if($move === 0){
				$pky = ($y - 1 + mt_rand(1, 5) / 10) + 1.62 + $plusY;
			}else{
				$pky = ($y - 1 + mt_rand(1, 5) / 10) + 1.62 + $plusY;
			}
			$pkz = $z - 1 + mt_rand(1,20) / 10;
			$pk->position = new Vector3($pkx, $pky, $pkz);
			$pk->motion = new Vector3(-0.2 + mt_rand(1,3) / 10, 0.2, -0.2 + mt_rand(1,3) / 10);
			$pk->yaw = 0;
			$pk->pitch = 0;
			$pk->item = Item::get(0);
			@$flags |= 0 << Entity::DATA_FLAG_INVISIBLE;
			@$flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
			@$flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
			@$flags |= 0 << Entity::DATA_FLAG_IMMOBILE;
			$pk->metadata = [
				Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
				Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $name],
				Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
 				Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0],//大きさ
				  	];
			$player->dataPacket($pk);
			$this->getScheduler()->scheduleDelayedTask(new FloatDelete($this, $player, $eid), 10);
		}
	}

	public function createWindow(Player $player, $data, int $id){
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
		$player->dataPacket($pk);
	}

 	public function FloatDelete($eid, $player){
		$pk = new RemoveEntityPacket();
		$pk->entityUniqueId = $eid;
		$player->dataPacket($pk);
	}

	public function heal($player, $item, $heal){
		$h = $player->getHealth();
		$m = $player->getMaxHealth();
		if($h == $m){
			$player->sendMessage("§c体力が最大です");
		}elseif($h < $m){
			$h = $h + $heal;
			if($h >= $m){
				$player->setHealth($m);
				$player->getInventory()->removeItem($item);
			}elseif($h < $m){
				$player->setHealth($h);
				$player->getInventory()->removeItem($item);
			}
			$player->sendMessage("§a体力を".$heal."回復しました");
		}
	}

	public function broadcastMessage($message){
		foreach($this->getServer()->getOnlinePlayers() as $player) $player->sendMessage($message);
	}

	public function check($nbt, $item){
		$nbt_name = $nbt->getName();
		$item_name = $item->getName();
		return ($nbt_name === $item_name) ? true : false;
	}

	public function death($entity, $eid){
		if($entity instanceof Player){
			$pos = new Vector3(256, 78.3, 256);
			$entity->teleport($pos);
			$entity->setHealth($entity->getMaxHealth());
			$entity->setFood(20);
			$entity->extinguish();
			$entity->removeAllEffects();
			$entity->addEffect(new EffectInstance(Effect::getEffect(15), 5 * 20, 3, false));
			$entity->addTitle("§4You dead...", "", "20", "3", "20");
			$entity->save();
			$deathevent = new PlayerDeathEvent($entity,[],"");
			$this->getServer()->getPluginManager()->callEvent($deathevent);
			if($eid !== ""){
				$this->entity[$eid]["move"] = 0;
			}
		}
	}

	public function ChangeTag($player){
		$name = $player->getName();
		$level = $this->getLevel($name);
		$health = round($player->getHealth(),1);
		if($health >= 6){
			$color = "§a";
		}else{
			$color = "§c";
		}
		if($this->config[$name]->get("guild") == ""){
			$player->setNameTag("§eLv.".$level." §l§f".$name."§r\n".$color."❤".$health);	
			$player->setDisplayName('§eLv.'.$level.' §l§f'.$name.'§r');
		}else{
			$tag = $this->config[$name]->get("guild");
			$player->setNameTag("§b[§r".$tag."§r§b] §eLv.".$level." §l§f".$name."§r\n".$color."❤".$health);	
			$player->setDisplayName('§b[§r'.$tag.'§r§b] §eLv.'.$level.' §l§f'.$name.'§r');	
		}
	}

	public function setDisplay(Player $player, string $tag){
		$remove = new RemoveEntityPacket();
		$remove->entityUniqueId = $player->getId();
		$pk = new AddPlayerPacket();
		$pk->uuid = $player->getUniqueId();
		$pk->username = $tag;
		$pk->entityRuntimeId = $player->getId();
		$pk->position = $player->asVector3();
		$pk->motion = $player->getMotion();
		$pk->yaw = $player->yaw;
		$pk->pitch = $player->pitch;
		$pk->item = $player->getInventory()->getItemInHand();
		$pk->metadata = $player->getDataPropertyManager()->getAll();
		foreach($this->getServer()->getOnlinePlayers() as $players){
			if($players->getId() !== $player->getId()){
				$players->dataPacket($remove);
				$players->dataPacket($pk);
			}
		}
	}

	public function getFolder($name){
		$sub = substr($name, 0, 1);
		$upper = strtoupper($sub);
		if($this->setting->get("config") === "on"){
			$folder = "/var/lib/pufferd/servers/3ad417db-b532-4abc-bb93-0680c45b0f02/fiercebattle/data2/".$upper.'/';
		}else{
			$folder = $this->getDataFolder().$upper.'/';
		}
		if(!file_exists($folder)) mkdir($folder);
		$lower = strtolower($name);
		return $folder .= $lower.'.json';
	}

	public function addExpLevel($player, $exp){
		$name = $player->getName();
		$player1 = $this->server->getPlayer($name);
		$this->addExp($name, $exp);
		$exp = $this->getExp($name);
		$old = $this->getLevel($name);
		$new = $old;
		while($exp >= $this->getExpectedExperience($new)) ++$new;
		while($exp < $this->getExpectedExperience($new - 1)) --$new;
		$this->setLevel($name, $new);
		if($old < $new){
			$pk = new SetTitlePacket();
			$pk->type = SetTitlePacket::TYPE_SET_TITLE;
			$pk->text = '§eレベルｱｯﾌﾟ Lv.'.$old.' -> Lv.'.$new.'';
			$player1->dataPacket($pk);
			$new_level = $new - $old;
			$this->ChangeTag($player1);
			$this->addStatus($player1->getName(), "point", 1);
			$player1->save();
			$this->config[$player1->getName()]->save();
		}
	}

	public function getLevelUpExpectedExperience($level, $exp){
		$expected = $this->getExpectedExperience($level);
		return $expected - $exp;
	}

	public function getExpectedExperience($level){
		return $level ** 3 * 3;
	}

	public function getJob($name){
		return $this->config[$name]->get("job");
	}

	public function setJob($name, $job){
		$this->config[$name]->set("job", $job);
	}

	public function getLevel($name){
		$job = $this->getJob($name);
		$data = $this->config[$name]->getAll();
		return $data[$job]["level"];
	}

	public function getExp($name){
		$job = $this->getJob($name);
		$data = $this->config[$name]->getAll();
		return $data[$job]["exp"];
	}

	public function addExp($name, $exp){
		$exp = $this->getExp($name) + $exp;
		$job = $this->getJob($name);
		$data = $this->config[$name]->getAll();
		$data[$job]["exp"] = $exp;
		$this->config[$name]->setAll($data);
	}

	public function addLevel($name,$level){
		$plus = $this->getLevel($name) + $level;
		$this->setLevel($name,$plus);
	}

	public function setLevel($name,$level){
		$job = $this->getJob($name);
		$data = $this->config[$name]->getAll();
		$data[$job]["level"] = $level;
		$this->config[$name]->setAll($data);
	}

	public function getMoney($name){
		return $this->config[$name]->get("money");
	}

	public function setMoney($name,$money){
		$this->config[$name]->set("money", $money);
	}

	public function addMoney($name,$money){
		$plus = $this->getMoney($name) + $money;
		$this->setMoney($name,$plus);
	}

	public function removeMoney($name,$money){
		$remove = $this->getMoney($name) - $money;
		$this->setMoney($name,$remove);
	}

	public function sendremoveMoney($sendname, $money){
		$money = $this->getMoney($sendname) - $money;
		$this->setMoney($sendname, $money);
	}

	public function getOrb($name){
		return $this->config[$name]->get("orb");
	}

	public function setOrb($name, $count){
		$this->config[$name]->set("orb", $count);
	}

	public function getStatus($name){
		return $this->config[$name]->get("status");
	}

	public function setStatus($name, $type, $count){
		$data = $this->config[$name]->getAll();
		$data["status"][$type] = $count;
		$this->config[$name]->setAll($data);
	}

	public function addStatus($name, $type, $count){
		$s = $this->getStatus($name);
		$status = $s[$type];
		$this->setStatus($name, $type, $status + $count);		
	}

	public function showDamage ($name, $eid, $damage){
		if ($damage <= 1) $color = "7";
		elseif ($damage > 1 and $damage <= 5) $color = "e";
		elseif ($damage > 5 and $damage <= 10) $color = "6";
		elseif ($damage > 10 and $damage < 20) $color = "c";
		elseif ($damage >= 20) $color = "4";
		$motion = new Vector3(lcg_value() * 0.2 - 0.1, 0.2, lcg_value() * 0.2 - 0.1);
		$deid = mt_rand(100000, 10000000);
		$pk = new AddEntityPacket();
		$pk->entityUniqueId = $deid;
		$pk->entityRuntimeId = $deid;
		$pk->type = 64;
		$pk->position = new Vector3($this->entity[$eid]["x"], $this->entity[$eid]["y"] + 1, $this->entity[$eid]["z"]);
		$pk->speedX = $motion->x;
		$pk->speedY = $motion->y;
		$pk->speedZ = $motion->z;
		$pk->pitch = 0;
		$pk->yaw = 0;
		$flags = 0;
		$flags |= 0 << Entity::DATA_FLAG_INVISIBLE;
		$flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
		$flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
		$flags |= 0 << Entity::DATA_FLAG_NO_AI;
		$pk->metadata = [
			Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
			Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, "§l§".$color.$damage." §r§fDamage"],
			Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
		];
		foreach($this->getServer()->getOnlinePlayers() as $players){
			$players->dataPacket($pk);
			$this->getScheduler()->scheduleDelayedTask(new close($this, $deid, $players), 15);
		}
	}

	public function RarityMark($value){
		if($value === 0){
			$rarity = "§7☆☆☆☆☆";

		}elseif($value === 1){
			$rarity = "§e☆§7☆☆☆☆";

		}elseif($value === 2){
			$rarity = "§e☆☆§7☆☆☆";

		}elseif($value === 3){
			$rarity = "§e☆☆☆§7☆☆";

		}elseif($value === 4){
			$rarity = "§e☆☆☆☆§7☆";

		}elseif($value === 5){
			$rarity = "§e☆☆☆☆☆";

		}elseif($value === 6){
			$rarity = "§e☆☆☆☆☆☆";

		}elseif($value === 7){
			$rarity = "§e☆☆☆☆☆☆☆";

		}elseif($value === 8){
			$rarity = "§e☆☆☆☆☆☆☆☆";

		}elseif($value === 9){
			$rarity = "§e☆☆☆☆☆☆☆☆☆";

		}elseif($value === 10){
			$rarity = "§e☆☆☆☆☆☆☆☆☆☆";

		}
		return $rarity;
	}
}
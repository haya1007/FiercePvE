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

class MobDeath{
	public $main;

	public function __construct(main $main){
		$this->main = $main;
	}

	public function MobDeath($eid, $player){
		if(isset($this->main->entity[$eid])){
			$exp = $this->main->entity[$eid]["exp"];
			$gold = $this->main->entity[$eid]["gold"];
			$mob = $this->main->entity[$eid]["name"];
			$x = $this->main->entity[$eid]["x"];
			$y = $this->main->entity[$eid]["y"];
			$z = $this->main->entity[$eid]["z"];
			$pos = new Vector3($x, $y, $z);
			foreach($this->main->getServer()->getOnlinePlayers() as $players){
				$level = $players->getLevel();
				if(isset($this->main->entity[$eid]["boss"])){
					$minusrand = floor($this->main->atkeid[$name][$eid] / $this->main->entity[$eid]["maxhp"]) * 100;
						}else{
					$minusrand = 0;
				}
				$name = $players->getName();
				if(isset($this->main->atkeid[$name][$eid]) and $player->distanceSquared($players) <= pow(32,2)){
					if($this->main->atkeid[$name][$eid] >= floor($this->main->entity[$eid]["maxhp"] / 10)){
						if(isset($this->main->entity[$eid]["exp"]) and isset($this->main->entity[$eid]["gold"])){
							$rexp = mt_rand($exp * 0.8, $exp * 1.2);
							$rgold = mt_rand($gold * 0.8, $gold * 1.2);
							$contents = $players->getInventory()->getContents();
							if(isset($contents[17])){
								$custom_name = $contents[17]->getCustomName();
								$this->main->addmoney($name, $rgold);
								$this->main->addExpLevel($players, $rexp);
								$players->sendMessage('§b'.$mob.'を倒して§a'.$rgold.'M§bと§a'.$rexp.'exp§bをゲットした!');
							}else{
								$this->main->addmoney($name, $rgold);
								$this->main->addExpLevel($players, $rexp);
								$players->sendMessage('§b'.$mob.'を倒して§a'.$rgold.'M§bと§a'.$rexp.'exp§bをゲットした!');
							}
							$this->main->config[$name]->save();
							if($this->main->config[$name]->get("guild") === $this->main->config[$player->getName()]->get("guild")){
								$exp = intval($exp/5);
								$gold = intval($gold/5);
								$rexp = mt_rand($exp * 0.8, $exp * 1.2);
								$rgold = mt_rand($gold * 0.8, $gold * 1.2);
								$this->main->addmoney($players->getName(), $rgold);
								$this->main->addExpLevel($players, $rexp);
								$players->sendMessage("§7Guild>> ".$rexp."expと".$rgold."Mを手に入れた");
							}
							if(isset($this->main->entity[$eid]["drop1"]) and isset($this->main->entity[$eid]["dropkakuritu1"])){
								$drop = $this->main->entity[$eid]["drop1"];
								$rarity = $this->main->RarityMark($this->main->citem[$drop]["rarity"]);
								$item = Item::get($this->main->citem[$drop]["id"], $this->main->citem[$drop]["meta"], 1)->setCustomName($this->main->citem[$drop]["cname"]."\n§6レア度§r: ".$rarity."\n§6レア度§r: ".$rarity);
								if(isset($this->main->citem[$drop]["colorR"]) and isset($this->main->citem[$drop]["colorG"]) and isset($this->main->citem[$drop]["colorB"])){
									$color = new Color($this->main->citem[$drop]["colorR"], $this->main->citem[$drop]["colorG"], $this->main->citem[$drop]["colorB"]);
									$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
									$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
								}
								if(isset($this->main->citem[$drop]["name"])){
									$item->setNamedTagEntry(new StringTag("name", $this->main->citem[$drop]["name"]));
								}
								if(isset($this->main->citem[$drop]["atk"])){
									$item->setNamedTagEntry(new IntTag("status", $this->main->citem[$drop]["atk"]));
								}
								if(isset($this->main->citem[$drop]["def"])){
									$item->setNamedTagEntry(new IntTag("def", $this->main->citem[$drop]["def"]));
								}
								if(isset($this->main->citem[$drop]["sell"])){
									$item->setNamedTagEntry(new IntTag("sell", $this->main->citem[$drop]["sell"]));
								}
								if(isset($this->main->citem[$drop]["special"])){
									$item->setNamedTagEntry(new StringTag("special", $this->main->citem[$drop]["special"]));
								}
								if(isset($this->main->citem[$drop]["sinka"])){
									$item->setNamedTagEntry(new StringTag("sinka", $this->main->citem[$drop]["sinka"]));
								}
								if(isset($this->main->citem[$drop]["sozainame1"])){
									$item->setNamedTagEntry(new StringTag("sozainame1", $this->main->citem[$drop]["sozainame1"]));
								}
								if(isset($this->main->citem[$drop]["sozai1"])){
									$item->setNamedTagEntry(new StringTag("sozai1", $this->main->citem[$drop]["sozai1"]));
								}
								if(isset($this->main->citem[$drop]["kosuu1"])){
									$item->setNamedTagEntry(new StringTag("kosuu1", $this->main->citem[$drop]["kosuu1"]));
								}
								if(isset($this->main->citem[$drop]["job"])){
									$item->setNamedTagEntry(new StringTag("job", $this->main->citem[$drop]["job"]));				
								}
								if(isset($this->main->citem[$drop]["mp"])){
									$item->setNamedTagEntry(new IntTag("mp", $this->main->citem[$drop]["mp"]));				
								}
								if(isset($this->main->citem[$drop]["type"])){
									$item->setNamedTagEntry(new StringTag("type", $this->main->citem[$drop]["type"]));								
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["petname"])){
									$item->setNamedTagEntry(new StringTag("petname", $this->main->citem[$drop]["petname"]));
								}
								if(isset($this->main->citem[$drop]["petatk"])){
									$item->setNamedTagEntry(new IntTag("petatk", $this->main->citem[$drop]["petatk"]));
								}
								if(isset($this->main->citem[$drop]["petspeed"])){
									$item->setNamedTagEntry(new IntTag("petspeed", $this->main->citem[$drop]["petspeed"]));
								}
								if(isset($this->main->citem[$drop]["petrange"])){
									$item->setNamedTagEntry(new IntTag("petrange", $this->main->citem[$drop]["petrange"]));
								}
								if(isset($this->main->citem[$drop]["petatkspeed"])){
									$item->setNamedTagEntry(new IntTag("petatkspeed", $this->main->citem[$drop]["petatkspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbtype"])){
									$item->setNamedTagEntry(new IntTag("petbtype", $this->main->citem[$drop]["petbtype"]));
								}
								if(isset($this->main->citem[$drop]["petbspeed"])){
									$item->setNamedTagEntry(new IntTag("petbspeed", $this->main->citem[$drop]["petbspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbsize"])){
									$item->setNamedTagEntry(new IntTag("petbsize", $this->main->citem[$drop]["petbsize"]));
								}
								if(isset($this->main->citem[$drop]["petskinid"]) and isset($this->main->citem[$drop]["petskindata"]) and isset($this->main->citem[$drop]["petcapedata"]) and isset($this->main->citem[$drop]["petgeometryname"]) and isset($this->main->citem[$drop]["petgeometrydata"])){
									$item->setNamedTagEntry(new StringTag("petskinid", $this->main->citem[$drop]["petskinid"]));
									$item->setNamedTagEntry(new StringTag("petskindata", $this->main->citem[$drop]["petskindata"]));
									$item->setNamedTagEntry(new StringTag("petcapedata", $this->main->citem[$drop]["petcapedata"]));
									$item->setNamedTagEntry(new StringTag("petgeometryname", $this->main->citem[$drop]["petgeometryname"]));
									$item->setNamedTagEntry(new StringTag("petgeometrydata", $this->main->citem[$drop]["petgeometrydata"]));
								}
								$item->setNamedTagEntry(new IntTag("kyouka", 0));
								$dropp = $this->main->entity[$eid]["dropkakuritu1"];
								if($dropp - $minusrand < 1){
									$rand = mt_rand(1, 1);
								}else{
									$rand = mt_rand(1, $dropp - $minusrand);
								}
								if($rand === 1){
									$itemname = str_replace(PHP_EOL, '', $this->main->citem[$drop]["cname"]);
									if($dropp <= 10){
										$players->sendMessage('§7普通のドロップがおちた §r'.$itemname.'');
									}elseif($dropp > 10 and $dropp <= 1000){
										$players->sendMessage('§dレア泥がおちた! §r'.$itemname.'');
									}elseif($dropp > 1000){
										$players->sendMessage('§b物凄くレアドロがおちた!  §r'.$itemname.'');
									}elseif($dropp > 10000){
										$players->sendMessage('§a奇跡なぐらいのレア泥だ!  §r'.$itemname.'');
									}
									$players->getInventory()->addItem($item);
								}
							}
							if(isset($this->main->entity[$eid]["drop2"]) and isset($this->main->entity[$eid]["dropkakuritu2"])){
								$drop = $this->main->entity[$eid]["drop2"];
								$rarity = $this->main->RarityMark($this->main->citem[$drop]["rarity"]);
								$item = Item::get($this->main->citem[$drop]["id"], $this->main->citem[$drop]["meta"], 1)->setCustomName($this->main->citem[$drop]["cname"]."\n§6レア度§r: ".$rarity);
								if(isset($this->main->citem[$drop]["colorR"]) and isset($this->main->citem[$drop]["colorG"]) and isset($this->main->citem[$drop]["colorB"])){
									$color = new Color($this->main->citem[$drop]["colorR"], $this->main->citem[$drop]["colorG"], $this->main->citem[$drop]["colorB"]);
									$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
									$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
								}
								if(isset($this->main->citem[$drop]["name"])){
									$item->setNamedTagEntry(new StringTag("name", $this->main->citem[$drop]["name"]));
								}
								if(isset($this->main->citem[$drop]["atk"])){
									$item->setNamedTagEntry(new IntTag("status", $this->main->citem[$drop]["atk"]));
								}
								if(isset($this->main->citem[$drop]["def"])){
									$item->setNamedTagEntry(new IntTag("def", $this->main->citem[$drop]["def"]));
								}
								if(isset($this->main->citem[$drop]["sell"])){
									$item->setNamedTagEntry(new IntTag("sell", $this->main->citem[$drop]["sell"]));
								}
								if(isset($this->main->citem[$drop]["special"])){
									$item->setNamedTagEntry(new StringTag("special", $this->main->citem[$drop]["special"]));
								}
								if(isset($this->main->citem[$drop]["sinka"])){
									$item->setNamedTagEntry(new StringTag("sinka", $this->main->citem[$drop]["sinka"]));
								}
								if(isset($this->main->citem[$drop]["sozainame1"])){
									$item->setNamedTagEntry(new StringTag("sozainame1", $this->main->citem[$drop]["sozainame1"]));
								}
								if(isset($this->main->citem[$drop]["sozai1"])){
									$item->setNamedTagEntry(new StringTag("sozai1", $this->main->citem[$drop]["sozai1"]));
								}
								if(isset($this->main->citem[$drop]["kosuu1"])){
									$item->setNamedTagEntry(new StringTag("kosuu1", $this->main->citem[$drop]["kosuu1"]));
								}
								if(isset($this->main->citem[$drop]["job"])){
									$item->setNamedTagEntry(new StringTag("job", $this->main->citem[$drop]["job"]));				
								}
								if(isset($this->main->citem[$drop]["mp"])){
									$item->setNamedTagEntry(new IntTag("mp", $this->main->citem[$drop]["mp"]));				
								}
								if(isset($this->main->citem[$drop]["type"])){
									$item->setNamedTagEntry(new StringTag("type", $this->main->citem[$drop]["type"]));								
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["petname"])){
									$item->setNamedTagEntry(new StringTag("petname", $this->main->citem[$drop]["petname"]));
								}
								if(isset($this->main->citem[$drop]["petatk"])){
									$item->setNamedTagEntry(new IntTag("petatk", $this->main->citem[$drop]["petatk"]));
								}
								if(isset($this->main->citem[$drop]["petspeed"])){
									$item->setNamedTagEntry(new IntTag("petspeed", $this->main->citem[$drop]["petspeed"]));
								}
								if(isset($this->main->citem[$drop]["petrange"])){
									$item->setNamedTagEntry(new IntTag("petrange", $this->main->citem[$drop]["petrange"]));
								}
								if(isset($this->main->citem[$drop]["petatkspeed"])){
									$item->setNamedTagEntry(new IntTag("petatkspeed", $this->main->citem[$drop]["petatkspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbtype"])){
									$item->setNamedTagEntry(new IntTag("petbtype", $this->main->citem[$drop]["petbtype"]));
								}
								if(isset($this->main->citem[$drop]["petbspeed"])){
									$item->setNamedTagEntry(new IntTag("petbspeed", $this->main->citem[$drop]["petbspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbsize"])){
									$item->setNamedTagEntry(new IntTag("petbsize", $this->main->citem[$drop]["petbsize"]));
								}
								if(isset($this->main->citem[$drop]["petskinid"]) and isset($this->main->citem[$drop]["petskindata"]) and isset($this->main->citem[$drop]["petcapedata"]) and isset($this->main->citem[$drop]["petgeometryname"]) and isset($this->main->citem[$drop]["petgeometrydata"])){
									$item->setNamedTagEntry(new StringTag("petskinid", $this->main->citem[$drop]["petskinid"]));
									$item->setNamedTagEntry(new StringTag("petskindata", $this->main->citem[$drop]["petskindata"]));
									$item->setNamedTagEntry(new StringTag("petcapedata", $this->main->citem[$drop]["petcapedata"]));
									$item->setNamedTagEntry(new StringTag("petgeometryname", $this->main->citem[$drop]["petgeometryname"]));
									$item->setNamedTagEntry(new StringTag("petgeometrydata", $this->main->citem[$drop]["petgeometrydata"]));
								}
								$item->setNamedTagEntry(new IntTag("kyouka", 0));
								$dropp = $this->main->entity[$eid]["dropkakuritu2"];
								if($dropp - $minusrand < 1){
									$rand = mt_rand(1, 1);
								}else{
									$rand = mt_rand(1, $dropp - $minusrand);
								}
								if($rand === 1){
									$itemname = str_replace(PHP_EOL, '', $this->main->citem[$drop]["cname"]);
									if($dropp <= 10){
										$players->sendMessage('§7普通のドロップがおちた §r'.$itemname.'');
									}elseif($dropp > 10 and $dropp <= 1000){
										$players->sendMessage('§dレア泥がおちた! §r'.$itemname.'');
									}elseif($dropp > 1000){
										$players->sendMessage('§b物凄くレアドロがおちた!  §r'.$itemname.'');
									}elseif($dropp > 10000){
										$players->sendMessage('§a奇跡なぐらいのレア泥だ!  §r'.$itemname.'');
									}
									$players->getInventory()->addItem($item);
								}
							}
							if(isset($this->main->entity[$eid]["drop3"]) and isset($this->main->entity[$eid]["dropkakuritu3"])){
								$drop = $this->main->entity[$eid]["drop3"];
								$rarity = $this->main->RarityMark($this->main->citem[$drop]["rarity"]);
								$item = Item::get($this->main->citem[$drop]["id"], $this->main->citem[$drop]["meta"], 1)->setCustomName($this->main->citem[$drop]["cname"]."\n§6レア度§r: ".$rarity);
								if(isset($this->main->citem[$drop]["colorR"]) and isset($this->main->citem[$drop]["colorG"]) and isset($this->main->citem[$drop]["colorB"])){
									$color = new Color($this->main->citem[$drop]["colorR"], $this->main->citem[$drop]["colorG"], $this->main->citem[$drop]["colorB"]);
									$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
									$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
								}
								if(isset($this->main->citem[$drop]["name"])){
									$item->setNamedTagEntry(new StringTag("name", $this->main->citem[$drop]["name"]));
								}
								if(isset($this->main->citem[$drop]["atk"])){
									$item->setNamedTagEntry(new IntTag("status", $this->main->citem[$drop]["atk"]));
								}
								if(isset($this->main->citem[$drop]["def"])){
									$item->setNamedTagEntry(new IntTag("def", $this->main->citem[$drop]["def"]));
								}
								if(isset($this->main->citem[$drop]["sell"])){
									$item->setNamedTagEntry(new IntTag("sell", $this->main->citem[$drop]["sell"]));
								}
								if(isset($this->main->citem[$drop]["special"])){
									$item->setNamedTagEntry(new StringTag("special", $this->main->citem[$drop]["special"]));
								}
								if(isset($this->main->citem[$drop]["sinka"])){
									$item->setNamedTagEntry(new StringTag("sinka", $this->main->citem[$drop]["sinka"]));
								}
								if(isset($this->main->citem[$drop]["sozainame1"])){
									$item->setNamedTagEntry(new StringTag("sozainame1", $this->main->citem[$drop]["sozainame1"]));
								}
								if(isset($this->main->citem[$drop]["sozai1"])){
									$item->setNamedTagEntry(new StringTag("sozai1", $this->main->citem[$drop]["sozai1"]));
								}
								if(isset($this->main->citem[$drop]["kosuu1"])){
									$item->setNamedTagEntry(new StringTag("kosuu1", $this->main->citem[$drop]["kosuu1"]));
								}
								if(isset($this->main->citem[$drop]["job"])){
									$item->setNamedTagEntry(new StringTag("job", $this->main->citem[$drop]["job"]));				
								}
								if(isset($this->main->citem[$drop]["mp"])){
									$item->setNamedTagEntry(new IntTag("mp", $this->main->citem[$drop]["mp"]));				
								}
								if(isset($this->main->citem[$drop]["type"])){
									$item->setNamedTagEntry(new StringTag("type", $this->main->citem[$drop]["type"]));								
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["petname"])){
									$item->setNamedTagEntry(new StringTag("petname", $this->main->citem[$drop]["petname"]));
								}
								if(isset($this->main->citem[$drop]["petatk"])){
									$item->setNamedTagEntry(new IntTag("petatk", $this->main->citem[$drop]["petatk"]));
								}
								if(isset($this->main->citem[$drop]["petspeed"])){
									$item->setNamedTagEntry(new IntTag("petspeed", $this->main->citem[$drop]["petspeed"]));
								}
								if(isset($this->main->citem[$drop]["petrange"])){
									$item->setNamedTagEntry(new IntTag("petrange", $this->main->citem[$drop]["petrange"]));
								}
								if(isset($this->main->citem[$drop]["petatkspeed"])){
									$item->setNamedTagEntry(new IntTag("petatkspeed", $this->main->citem[$drop]["petatkspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbtype"])){
									$item->setNamedTagEntry(new IntTag("petbtype", $this->main->citem[$drop]["petbtype"]));
								}
								if(isset($this->main->citem[$drop]["petbspeed"])){
									$item->setNamedTagEntry(new IntTag("petbspeed", $this->main->citem[$drop]["petbspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbsize"])){
									$item->setNamedTagEntry(new IntTag("petbsize", $this->main->citem[$drop]["petbsize"]));
								}
								if(isset($this->main->citem[$drop]["petskinid"]) and isset($this->main->citem[$drop]["petskindata"]) and isset($this->main->citem[$drop]["petcapedata"]) and isset($this->main->citem[$drop]["petgeometryname"]) and isset($this->main->citem[$drop]["petgeometrydata"])){
									$item->setNamedTagEntry(new StringTag("petskinid", $this->main->citem[$drop]["petskinid"]));
									$item->setNamedTagEntry(new StringTag("petskindata", $this->main->citem[$drop]["petskindata"]));
									$item->setNamedTagEntry(new StringTag("petcapedata", $this->main->citem[$drop]["petcapedata"]));
									$item->setNamedTagEntry(new StringTag("petgeometryname", $this->main->citem[$drop]["petgeometryname"]));
									$item->setNamedTagEntry(new StringTag("petgeometrydata", $this->main->citem[$drop]["petgeometrydata"]));
								}
								$item->setNamedTagEntry(new IntTag("kyouka", 0));
								$dropp = $this->main->entity[$eid]["dropkakuritu3"];
								if($dropp - $minusrand < 1){
									$rand = mt_rand(1, 1);
								}else{
									$rand = mt_rand(1, $dropp - $minusrand);
								}
								if($rand === 1){
									$itemname = str_replace(PHP_EOL, '', $this->main->citem[$drop]["cname"]);
									if($dropp <= 10){
										$players->sendMessage('§7普通のドロップがおちた §r'.$itemname.'');
									}elseif($dropp > 10 and $dropp <= 1000){
										$players->sendMessage('§dレア泥がおちた! §r'.$itemname.'');
									}elseif($dropp > 1000){
										$players->sendMessage('§b物凄くレアドロがおちた!  §r'.$itemname.'');
									}elseif($dropp > 10000){
										$players->sendMessage('§a奇跡なぐらいのレア泥だ!  §r'.$itemname.'');
									}
									$players->getInventory()->addItem($item);
								}
							}
							if(isset($this->main->entity[$eid]["drop4"]) and isset($this->main->entity[$eid]["dropkakuritu4"])){
								$drop = $this->main->entity[$eid]["drop4"];
								$rarity = $this->main->RarityMark($this->main->citem[$drop]["rarity"]);
								$item = Item::get($this->main->citem[$drop]["id"], $this->main->citem[$drop]["meta"], 1)->setCustomName($this->main->citem[$drop]["cname"]."\n§6レア度§r: ".$rarity);
								if(isset($this->main->citem[$drop]["colorR"]) and isset($this->main->citem[$drop]["colorG"]) and isset($this->main->citem[$drop]["colorB"])){
									$color = new Color($this->main->citem[$drop]["colorR"], $this->main->citem[$drop]["colorG"], $this->main->citem[$drop]["colorB"]);
									$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
									$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
								}
								if(isset($this->main->citem[$drop]["name"])){
									$item->setNamedTagEntry(new StringTag("name", $this->main->citem[$drop]["name"]));
								}
								if(isset($this->main->citem[$drop]["atk"])){
									$item->setNamedTagEntry(new IntTag("status", $this->main->citem[$drop]["atk"]));
								}
								if(isset($this->main->citem[$drop]["def"])){
									$item->setNamedTagEntry(new IntTag("def", $this->main->citem[$drop]["def"]));
								}
								if(isset($this->main->citem[$drop]["sell"])){
									$item->setNamedTagEntry(new IntTag("sell", $this->main->citem[$drop]["sell"]));
								}
								if(isset($this->main->citem[$drop]["special"])){
									$item->setNamedTagEntry(new StringTag("special", $this->main->citem[$drop]["special"]));
								}
								if(isset($this->main->citem[$drop]["sinka"])){
									$item->setNamedTagEntry(new StringTag("sinka", $this->main->citem[$drop]["sinka"]));
								}
								if(isset($this->main->citem[$drop]["sozainame1"])){
									$item->setNamedTagEntry(new StringTag("sozainame1", $this->main->citem[$drop]["sozainame1"]));
								}
								if(isset($this->main->citem[$drop]["sozai1"])){
									$item->setNamedTagEntry(new StringTag("sozai1", $this->main->citem[$drop]["sozai1"]));
								}
								if(isset($this->main->citem[$drop]["kosuu1"])){
									$item->setNamedTagEntry(new StringTag("kosuu1", $this->main->citem[$drop]["kosuu1"]));
								}
								if(isset($this->main->citem[$drop]["job"])){
									$item->setNamedTagEntry(new StringTag("job", $this->main->citem[$drop]["job"]));				
								}
								if(isset($this->main->citem[$drop]["mp"])){
									$item->setNamedTagEntry(new IntTag("mp", $this->main->citem[$drop]["mp"]));				
								}
								if(isset($this->main->citem[$drop]["type"])){
									$item->setNamedTagEntry(new StringTag("type", $this->main->citem[$drop]["type"]));								
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["petname"])){
									$item->setNamedTagEntry(new StringTag("petname", $this->main->citem[$drop]["petname"]));
								}
								if(isset($this->main->citem[$drop]["petatk"])){
									$item->setNamedTagEntry(new IntTag("petatk", $this->main->citem[$drop]["petatk"]));
								}
								if(isset($this->main->citem[$drop]["petspeed"])){
									$item->setNamedTagEntry(new IntTag("petspeed", $this->main->citem[$drop]["petspeed"]));
								}
								if(isset($this->main->citem[$drop]["petrange"])){
									$item->setNamedTagEntry(new IntTag("petrange", $this->main->citem[$drop]["petrange"]));
								}
								if(isset($this->main->citem[$drop]["petatkspeed"])){
									$item->setNamedTagEntry(new IntTag("petatkspeed", $this->main->citem[$drop]["petatkspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbtype"])){
									$item->setNamedTagEntry(new IntTag("petbtype", $this->main->citem[$drop]["petbtype"]));
								}
								if(isset($this->main->citem[$drop]["petbspeed"])){
									$item->setNamedTagEntry(new IntTag("petbspeed", $this->main->citem[$drop]["petbspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbsize"])){
									$item->setNamedTagEntry(new IntTag("petbsize", $this->main->citem[$drop]["petbsize"]));
								}
								if(isset($this->main->citem[$drop]["petskinid"]) and isset($this->main->citem[$drop]["petskindata"]) and isset($this->main->citem[$drop]["petcapedata"]) and isset($this->main->citem[$drop]["petgeometryname"]) and isset($this->main->citem[$drop]["petgeometrydata"])){
									$item->setNamedTagEntry(new StringTag("petskinid", $this->main->citem[$drop]["petskinid"]));
									$item->setNamedTagEntry(new StringTag("petskindata", $this->main->citem[$drop]["petskindata"]));
									$item->setNamedTagEntry(new StringTag("petcapedata", $this->main->citem[$drop]["petcapedata"]));
									$item->setNamedTagEntry(new StringTag("petgeometryname", $this->main->citem[$drop]["petgeometryname"]));
									$item->setNamedTagEntry(new StringTag("petgeometrydata", $this->main->citem[$drop]["petgeometrydata"]));
								}
								$item->setNamedTagEntry(new IntTag("kyouka", 0));
								$dropp = $this->main->entity[$eid]["dropkakuritu4"];
								if($dropp - $minusrand < 1){
									$rand = mt_rand(1, 1);
								}else{
									$rand = mt_rand(1, $dropp - $minusrand);
								}
								if($rand === 1){
									$itemname = str_replace(PHP_EOL, '', $this->main->citem[$drop]["cname"]);
									if($dropp <= 10){
										$players->sendMessage('§7普通のドロップがおちた §r'.$itemname.'');
									}elseif($dropp > 10 and $dropp <= 1000){
										$players->sendMessage('§dレア泥がおちた! §r'.$itemname.'');
									}elseif($dropp > 1000){
										$players->sendMessage('§b物凄くレアドロがおちた!  §r'.$itemname.'');
									}elseif($dropp > 10000){
										$players->sendMessage('§a奇跡なぐらいのレア泥だ!  §r'.$itemname.'');
									}
									$players->getInventory()->addItem($item);
								}
							}
							if(isset($this->main->entity[$eid]["drop5"]) and isset($this->main->entity[$eid]["dropkakuritu5"])){
								$drop = $this->main->entity[$eid]["drop5"];
								$rarity = $this->main->RarityMark($this->main->citem[$drop]["rarity"]);
								$item = Item::get($this->main->citem[$drop]["id"], $this->main->citem[$drop]["meta"], 1)->setCustomName($this->main->citem[$drop]["cname"]."\n§6レア度§r: ".$rarity);
								if(isset($this->main->citem[$drop]["colorR"]) and isset($this->main->citem[$drop]["colorG"]) and isset($this->main->citem[$drop]["colorB"])){
									$color = new Color($this->main->citem[$drop]["colorR"], $this->main->citem[$drop]["colorG"], $this->main->citem[$drop]["colorB"]);
									$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
									$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
								}
								if(isset($this->main->citem[$drop]["name"])){
									$item->setNamedTagEntry(new StringTag("name", $this->main->citem[$drop]["name"]));
								}
								if(isset($this->main->citem[$drop]["atk"])){
									$item->setNamedTagEntry(new IntTag("status", $this->main->citem[$drop]["atk"]));
								}
								if(isset($this->main->citem[$drop]["def"])){
									$item->setNamedTagEntry(new IntTag("def", $this->main->citem[$drop]["def"]));
								}
								if(isset($this->main->citem[$drop]["sell"])){
									$item->setNamedTagEntry(new IntTag("sell", $this->main->citem[$drop]["sell"]));
								}
								if(isset($this->main->citem[$drop]["special"])){
									$item->setNamedTagEntry(new StringTag("special", $this->main->citem[$drop]["special"]));
								}
								if(isset($this->main->citem[$drop]["sinka"])){
									$item->setNamedTagEntry(new StringTag("sinka", $this->main->citem[$drop]["sinka"]));
								}
								if(isset($this->main->citem[$drop]["sozainame1"])){
									$item->setNamedTagEntry(new StringTag("sozainame1", $this->main->citem[$drop]["sozainame1"]));
								}
								if(isset($this->main->citem[$drop]["sozai1"])){
									$item->setNamedTagEntry(new StringTag("sozai1", $this->main->citem[$drop]["sozai1"]));
								}
								if(isset($this->main->citem[$drop]["kosuu1"])){
									$item->setNamedTagEntry(new StringTag("kosuu1", $this->main->citem[$drop]["kosuu1"]));
								}
								if(isset($this->main->citem[$drop]["job"])){
									$item->setNamedTagEntry(new StringTag("job", $this->main->citem[$drop]["job"]));				
								}
								if(isset($this->main->citem[$drop]["mp"])){
									$item->setNamedTagEntry(new IntTag("mp", $this->main->citem[$drop]["mp"]));				
								}
								if(isset($this->main->citem[$drop]["type"])){
									$item->setNamedTagEntry(new StringTag("type", $this->main->citem[$drop]["type"]));								
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["petname"])){
									$item->setNamedTagEntry(new StringTag("petname", $this->main->citem[$drop]["petname"]));
								}
								if(isset($this->main->citem[$drop]["petatk"])){
									$item->setNamedTagEntry(new IntTag("petatk", $this->main->citem[$drop]["petatk"]));
								}
								if(isset($this->main->citem[$drop]["petspeed"])){
									$item->setNamedTagEntry(new IntTag("petspeed", $this->main->citem[$drop]["petspeed"]));
								}
								if(isset($this->main->citem[$drop]["petrange"])){
									$item->setNamedTagEntry(new IntTag("petrange", $this->main->citem[$drop]["petrange"]));
								}
								if(isset($this->main->citem[$drop]["petatkspeed"])){
									$item->setNamedTagEntry(new IntTag("petatkspeed", $this->main->citem[$drop]["petatkspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbtype"])){
									$item->setNamedTagEntry(new IntTag("petbtype", $this->main->citem[$drop]["petbtype"]));
								}
								if(isset($this->main->citem[$drop]["petbspeed"])){
									$item->setNamedTagEntry(new IntTag("petbspeed", $this->main->citem[$drop]["petbspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbsize"])){
									$item->setNamedTagEntry(new IntTag("petbsize", $this->main->citem[$drop]["petbsize"]));
								}
								if(isset($this->main->citem[$drop]["petskinid"]) and isset($this->main->citem[$drop]["petskindata"]) and isset($this->main->citem[$drop]["petcapedata"]) and isset($this->main->citem[$drop]["petgeometryname"]) and isset($this->main->citem[$drop]["petgeometrydata"])){
									$item->setNamedTagEntry(new StringTag("petskinid", $this->main->citem[$drop]["petskinid"]));
									$item->setNamedTagEntry(new StringTag("petskindata", $this->main->citem[$drop]["petskindata"]));
									$item->setNamedTagEntry(new StringTag("petcapedata", $this->main->citem[$drop]["petcapedata"]));
									$item->setNamedTagEntry(new StringTag("petgeometryname", $this->main->citem[$drop]["petgeometryname"]));
									$item->setNamedTagEntry(new StringTag("petgeometrydata", $this->main->citem[$drop]["petgeometrydata"]));
								}
								$item->setNamedTagEntry(new IntTag("kyouka", 0));
								$dropp = $this->main->entity[$eid]["dropkakuritu5"];
								if($dropp - $minusrand < 1){
									$rand = mt_rand(1, 1);
								}else{
									$rand = mt_rand(1, $dropp - $minusrand);
								}
								if($rand === 1){
									$itemname = str_replace(PHP_EOL, '', $this->main->citem[$drop]["cname"]);
									if($dropp <= 10){
										$players->sendMessage('§7普通のドロップがおちた §r'.$itemname.'');
									}elseif($dropp > 10 and $dropp <= 1000){
										$players->sendMessage('§dレア泥がおちた! §r'.$itemname.'');
									}elseif($dropp > 1000){
										$players->sendMessage('§b物凄くレアドロがおちた!  §r'.$itemname.'');
									}elseif($dropp > 10000){
										$players->sendMessage('§a奇跡なぐらいのレア泥だ!  §r'.$itemname.'');
									}
									$players->getInventory()->addItem($item);
								}
							}
							if(isset($this->main->entity[$eid]["drop6"]) and isset($this->main->entity[$eid]["dropkakuritu6"])){
								$drop = $this->main->entity[$eid]["drop6"];
								$rarity = $this->main->RarityMark($this->main->citem[$drop]["rarity"]);
								$item = Item::get($this->main->citem[$drop]["id"], $this->main->citem[$drop]["meta"], 1)->setCustomName($this->main->citem[$drop]["cname"]."\n§6レア度§r: ".$rarity);
								if(isset($this->main->citem[$drop]["colorR"]) and isset($this->main->citem[$drop]["colorG"]) and isset($this->main->citem[$drop]["colorB"])){
									$color = new Color($this->main->citem[$drop]["colorR"], $this->main->citem[$drop]["colorG"], $this->main->citem[$drop]["colorB"]);
									$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
									$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
								}
								if(isset($this->main->citem[$drop]["name"])){
									$item->setNamedTagEntry(new StringTag("name", $this->main->citem[$drop]["name"]));
								}
								if(isset($this->main->citem[$drop]["atk"])){
									$item->setNamedTagEntry(new IntTag("status", $this->main->citem[$drop]["atk"]));
								}
								if(isset($this->main->citem[$drop]["def"])){
									$item->setNamedTagEntry(new IntTag("def", $this->main->citem[$drop]["def"]));
								}
								if(isset($this->main->citem[$drop]["sell"])){
									$item->setNamedTagEntry(new IntTag("sell", $this->main->citem[$drop]["sell"]));
								}
								if(isset($this->main->citem[$drop]["special"])){
									$item->setNamedTagEntry(new StringTag("special", $this->main->citem[$drop]["special"]));
								}
								if(isset($this->main->citem[$drop]["sinka"])){
									$item->setNamedTagEntry(new StringTag("sinka", $this->main->citem[$drop]["sinka"]));
								}
								if(isset($this->main->citem[$drop]["sozainame1"])){
									$item->setNamedTagEntry(new StringTag("sozainame1", $this->main->citem[$drop]["sozainame1"]));
								}
								if(isset($this->main->citem[$drop]["sozai1"])){
									$item->setNamedTagEntry(new StringTag("sozai1", $this->main->citem[$drop]["sozai1"]));
								}
								if(isset($this->main->citem[$drop]["kosuu1"])){
									$item->setNamedTagEntry(new StringTag("kosuu1", $this->main->citem[$drop]["kosuu1"]));
								}
								if(isset($this->main->citem[$drop]["job"])){
									$item->setNamedTagEntry(new StringTag("job", $this->main->citem[$drop]["job"]));				
								}
								if(isset($this->main->citem[$drop]["mp"])){
									$item->setNamedTagEntry(new IntTag("mp", $this->main->citem[$drop]["mp"]));				
								}
								if(isset($this->main->citem[$drop]["type"])){
									$item->setNamedTagEntry(new StringTag("type", $this->main->citem[$drop]["type"]));								
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["petname"])){
									$item->setNamedTagEntry(new StringTag("petname", $this->main->citem[$drop]["petname"]));
								}
								if(isset($this->main->citem[$drop]["petatk"])){
									$item->setNamedTagEntry(new IntTag("petatk", $this->main->citem[$drop]["petatk"]));
								}
								if(isset($this->main->citem[$drop]["petspeed"])){
									$item->setNamedTagEntry(new IntTag("petspeed", $this->main->citem[$drop]["petspeed"]));
								}
								if(isset($this->main->citem[$drop]["petrange"])){
									$item->setNamedTagEntry(new IntTag("petrange", $this->main->citem[$drop]["petrange"]));
								}
								if(isset($this->main->citem[$drop]["petatkspeed"])){
									$item->setNamedTagEntry(new IntTag("petatkspeed", $this->main->citem[$drop]["petatkspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbtype"])){
									$item->setNamedTagEntry(new IntTag("petbtype", $this->main->citem[$drop]["petbtype"]));
								}
								if(isset($this->main->citem[$drop]["petbspeed"])){
									$item->setNamedTagEntry(new IntTag("petbspeed", $this->main->citem[$drop]["petbspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbsize"])){
									$item->setNamedTagEntry(new IntTag("petbsize", $this->main->citem[$drop]["petbsize"]));
								}
								if(isset($this->main->citem[$drop]["petskinid"]) and isset($this->main->citem[$drop]["petskindata"]) and isset($this->main->citem[$drop]["petcapedata"]) and isset($this->main->citem[$drop]["petgeometryname"]) and isset($this->main->citem[$drop]["petgeometrydata"])){
									$item->setNamedTagEntry(new StringTag("petskinid", $this->main->citem[$drop]["petskinid"]));
									$item->setNamedTagEntry(new StringTag("petskindata", $this->main->citem[$drop]["petskindata"]));
									$item->setNamedTagEntry(new StringTag("petcapedata", $this->main->citem[$drop]["petcapedata"]));
									$item->setNamedTagEntry(new StringTag("petgeometryname", $this->main->citem[$drop]["petgeometryname"]));
									$item->setNamedTagEntry(new StringTag("petgeometrydata", $this->main->citem[$drop]["petgeometrydata"]));
								}
								$item->setNamedTagEntry(new IntTag("kyouka", 0));
								$dropp = $this->main->entity[$eid]["dropkakuritu6"];
								if($dropp - $minusrand < 1){
									$rand = mt_rand(1, 1);
								}else{
									$rand = mt_rand(1, $dropp - $minusrand);
								}
								if($rand === 1){
									$itemname = str_replace(PHP_EOL, '', $this->main->citem[$drop]["cname"]);
									if($dropp <= 10){
										$players->sendMessage('§7普通のドロップがおちた §r'.$itemname.'');
									}elseif($dropp > 10 and $dropp <= 1000){
										$players->sendMessage('§dレア泥がおちた! §r'.$itemname.'');
									}elseif($dropp > 1000){
										$players->sendMessage('§b物凄くレアドロがおちた!  §r'.$itemname.'');
									}elseif($dropp > 10000){
										$players->sendMessage('§a奇跡なぐらいのレア泥だ!  §r'.$itemname.'');
									}
									$players->getInventory()->addItem($item);
								}
							}
							if(isset($this->main->entity[$eid]["drop7"]) and isset($this->main->entity[$eid]["dropkakuritu7"])){
								$drop = $this->main->entity[$eid]["drop7"];
								$rarity = $this->main->RarityMark($this->main->citem[$drop]["rarity"]);
								$item = Item::get($this->main->citem[$drop]["id"], $this->main->citem[$drop]["meta"], 1)->setCustomName($this->main->citem[$drop]["cname"]."\n§6レア度§r: ".$rarity);
								if(isset($this->main->citem[$drop]["colorR"]) and isset($this->main->citem[$drop]["colorG"]) and isset($this->main->citem[$drop]["colorB"])){
									$color = new Color($this->main->citem[$drop]["colorR"], $this->main->citem[$drop]["colorG"], $this->main->citem[$drop]["colorB"]);
									$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
									$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
								}
								if(isset($this->main->citem[$drop]["name"])){
									$item->setNamedTagEntry(new StringTag("name", $this->main->citem[$drop]["name"]));
								}
								if(isset($this->main->citem[$drop]["atk"])){
									$item->setNamedTagEntry(new IntTag("status", $this->main->citem[$drop]["atk"]));
								}
								if(isset($this->main->citem[$drop]["def"])){
									$item->setNamedTagEntry(new IntTag("def", $this->main->citem[$drop]["def"]));
								}
								if(isset($this->main->citem[$drop]["sell"])){
									$item->setNamedTagEntry(new IntTag("sell", $this->main->citem[$drop]["sell"]));
								}
								if(isset($this->main->citem[$drop]["special"])){
									$item->setNamedTagEntry(new StringTag("special", $this->main->citem[$drop]["special"]));
								}
								if(isset($this->main->citem[$drop]["sinka"])){
									$item->setNamedTagEntry(new StringTag("sinka", $this->main->citem[$drop]["sinka"]));
								}
								if(isset($this->main->citem[$drop]["sozainame1"])){
									$item->setNamedTagEntry(new StringTag("sozainame1", $this->main->citem[$drop]["sozainame1"]));
								}
								if(isset($this->main->citem[$drop]["sozai1"])){
									$item->setNamedTagEntry(new StringTag("sozai1", $this->main->citem[$drop]["sozai1"]));
								}
								if(isset($this->main->citem[$drop]["kosuu1"])){
									$item->setNamedTagEntry(new StringTag("kosuu1", $this->main->citem[$drop]["kosuu1"]));
								}
								if(isset($this->main->citem[$drop]["job"])){
									$item->setNamedTagEntry(new StringTag("job", $this->main->citem[$drop]["job"]));				
								}
								if(isset($this->main->citem[$drop]["mp"])){
									$item->setNamedTagEntry(new IntTag("mp", $this->main->citem[$drop]["mp"]));				
								}
								if(isset($this->main->citem[$drop]["type"])){
									$item->setNamedTagEntry(new StringTag("type", $this->main->citem[$drop]["type"]));								
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["petname"])){
									$item->setNamedTagEntry(new StringTag("petname", $this->main->citem[$drop]["petname"]));
								}
								if(isset($this->main->citem[$drop]["petatk"])){
									$item->setNamedTagEntry(new IntTag("petatk", $this->main->citem[$drop]["petatk"]));
								}
								if(isset($this->main->citem[$drop]["petspeed"])){
									$item->setNamedTagEntry(new IntTag("petspeed", $this->main->citem[$drop]["petspeed"]));
								}
								if(isset($this->main->citem[$drop]["petrange"])){
									$item->setNamedTagEntry(new IntTag("petrange", $this->main->citem[$drop]["petrange"]));
								}
								if(isset($this->main->citem[$drop]["petatkspeed"])){
									$item->setNamedTagEntry(new IntTag("petatkspeed", $this->main->citem[$drop]["petatkspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbtype"])){
									$item->setNamedTagEntry(new IntTag("petbtype", $this->main->citem[$drop]["petbtype"]));
								}
								if(isset($this->main->citem[$drop]["petbspeed"])){
									$item->setNamedTagEntry(new IntTag("petbspeed", $this->main->citem[$drop]["petbspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbsize"])){
									$item->setNamedTagEntry(new IntTag("petbsize", $this->main->citem[$drop]["petbsize"]));
								}
								if(isset($this->main->citem[$drop]["petskinid"]) and isset($this->main->citem[$drop]["petskindata"]) and isset($this->main->citem[$drop]["petcapedata"]) and isset($this->main->citem[$drop]["petgeometryname"]) and isset($this->main->citem[$drop]["petgeometrydata"])){
									$item->setNamedTagEntry(new StringTag("petskinid", $this->main->citem[$drop]["petskinid"]));
									$item->setNamedTagEntry(new StringTag("petskindata", $this->main->citem[$drop]["petskindata"]));
									$item->setNamedTagEntry(new StringTag("petcapedata", $this->main->citem[$drop]["petcapedata"]));
									$item->setNamedTagEntry(new StringTag("petgeometryname", $this->main->citem[$drop]["petgeometryname"]));
									$item->setNamedTagEntry(new StringTag("petgeometrydata", $this->main->citem[$drop]["petgeometrydata"]));
								}
								$item->setNamedTagEntry(new IntTag("kyouka", 0));
								$dropp = $this->main->entity[$eid]["dropkakuritu7"];
								if($dropp - $minusrand < 1){
									$rand = mt_rand(1, 1);
								}else{
									$rand = mt_rand(1, $dropp - $minusrand);
								}
								if($rand === 1){
									$itemname = str_replace(PHP_EOL, '', $this->main->citem[$drop]["cname"]);
									if($dropp <= 10){
										$players->sendMessage('§7普通のドロップがおちた §r'.$itemname.'');
									}elseif($dropp > 10 and $dropp <= 1000){
										$players->sendMessage('§dレア泥がおちた! §r'.$itemname.'');
									}elseif($dropp > 1000){
										$players->sendMessage('§b物凄くレアドロがおちた!  §r'.$itemname.'');
									}elseif($dropp > 10000){
										$players->sendMessage('§a奇跡なぐらいのレア泥だ!  §r'.$itemname.'');
									}
									$players->getInventory()->addItem($item);
								}
							}
							if(isset($this->main->entity[$eid]["drop8"]) and isset($this->main->entity[$eid]["dropkakuritu8"])){
								$drop = $this->main->entity[$eid]["drop8"];
								$rarity = $this->main->RarityMark($this->main->citem[$drop]["rarity"]);
								$item = Item::get($this->main->citem[$drop]["id"], $this->main->citem[$drop]["meta"], 1)->setCustomName($this->main->citem[$drop]["cname"]."\n§6レア度§r: ".$rarity);
								if(isset($this->main->citem[$drop]["colorR"]) and isset($this->main->citem[$drop]["colorG"]) and isset($this->main->citem[$drop]["colorB"])){
									$color = new Color($this->main->citem[$drop]["colorR"], $this->main->citem[$drop]["colorG"], $this->main->citem[$drop]["colorB"]);
									$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
									$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
								}
								if(isset($this->main->citem[$drop]["name"])){
									$item->setNamedTagEntry(new StringTag("name", $this->main->citem[$drop]["name"]));
								}
								if(isset($this->main->citem[$drop]["atk"])){
									$item->setNamedTagEntry(new IntTag("status", $this->main->citem[$drop]["atk"]));
								}
								if(isset($this->main->citem[$drop]["def"])){
									$item->setNamedTagEntry(new IntTag("def", $this->main->citem[$drop]["def"]));
								}
								if(isset($this->main->citem[$drop]["sell"])){
									$item->setNamedTagEntry(new IntTag("sell", $this->main->citem[$drop]["sell"]));
								}
								if(isset($this->main->citem[$drop]["special"])){
									$item->setNamedTagEntry(new StringTag("special", $this->main->citem[$drop]["special"]));
								}
								if(isset($this->main->citem[$drop]["sinka"])){
									$item->setNamedTagEntry(new StringTag("sinka", $this->main->citem[$drop]["sinka"]));
								}
								if(isset($this->main->citem[$drop]["sozainame1"])){
									$item->setNamedTagEntry(new StringTag("sozainame1", $this->main->citem[$drop]["sozainame1"]));
								}
								if(isset($this->main->citem[$drop]["sozai1"])){
									$item->setNamedTagEntry(new StringTag("sozai1", $this->main->citem[$drop]["sozai1"]));
								}
								if(isset($this->main->citem[$drop]["kosuu1"])){
									$item->setNamedTagEntry(new StringTag("kosuu1", $this->main->citem[$drop]["kosuu1"]));
								}
								if(isset($this->main->citem[$drop]["job"])){
									$item->setNamedTagEntry(new StringTag("job", $this->main->citem[$drop]["job"]));				
								}
								if(isset($this->main->citem[$drop]["mp"])){
									$item->setNamedTagEntry(new IntTag("mp", $this->main->citem[$drop]["mp"]));				
								}
								if(isset($this->main->citem[$drop]["type"])){
									$item->setNamedTagEntry(new StringTag("type", $this->main->citem[$drop]["type"]));								
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["petname"])){
									$item->setNamedTagEntry(new StringTag("petname", $this->main->citem[$drop]["petname"]));
								}
								if(isset($this->main->citem[$drop]["petatk"])){
									$item->setNamedTagEntry(new IntTag("petatk", $this->main->citem[$drop]["petatk"]));
								}
								if(isset($this->main->citem[$drop]["petspeed"])){
									$item->setNamedTagEntry(new IntTag("petspeed", $this->main->citem[$drop]["petspeed"]));
								}
								if(isset($this->main->citem[$drop]["petrange"])){
									$item->setNamedTagEntry(new IntTag("petrange", $this->main->citem[$drop]["petrange"]));
								}
								if(isset($this->main->citem[$drop]["petatkspeed"])){
									$item->setNamedTagEntry(new IntTag("petatkspeed", $this->main->citem[$drop]["petatkspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbtype"])){
									$item->setNamedTagEntry(new IntTag("petbtype", $this->main->citem[$drop]["petbtype"]));
								}
								if(isset($this->main->citem[$drop]["petbspeed"])){
									$item->setNamedTagEntry(new IntTag("petbspeed", $this->main->citem[$drop]["petbspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbsize"])){
									$item->setNamedTagEntry(new IntTag("petbsize", $this->main->citem[$drop]["petbsize"]));
								}
								if(isset($this->main->citem[$drop]["petskinid"]) and isset($this->main->citem[$drop]["petskindata"]) and isset($this->main->citem[$drop]["petcapedata"]) and isset($this->main->citem[$drop]["petgeometryname"]) and isset($this->main->citem[$drop]["petgeometrydata"])){
									$item->setNamedTagEntry(new StringTag("petskinid", $this->main->citem[$drop]["petskinid"]));
									$item->setNamedTagEntry(new StringTag("petskindata", $this->main->citem[$drop]["petskindata"]));
									$item->setNamedTagEntry(new StringTag("petcapedata", $this->main->citem[$drop]["petcapedata"]));
									$item->setNamedTagEntry(new StringTag("petgeometryname", $this->main->citem[$drop]["petgeometryname"]));
									$item->setNamedTagEntry(new StringTag("petgeometrydata", $this->main->citem[$drop]["petgeometrydata"]));
								}
								$item->setNamedTagEntry(new IntTag("kyouka", 0));
								$dropp = $this->main->entity[$eid]["dropkakuritu8"];
								if($dropp - $minusrand < 1){
									$rand = mt_rand(1, 1);
								}else{
									$rand = mt_rand(1, $dropp - $minusrand);
								}
								if($rand === 1){
									$itemname = str_replace(PHP_EOL, '', $this->main->citem[$drop]["cname"]);
									if($dropp <= 10){
										$players->sendMessage('§7普通のドロップがおちた §r'.$itemname.'');
									}elseif($dropp > 10 and $dropp <= 1000){
										$players->sendMessage('§dレア泥がおちた! §r'.$itemname.'');
									}elseif($dropp > 1000){
										$players->sendMessage('§b物凄くレアドロがおちた!  §r'.$itemname.'');
									}elseif($dropp > 10000){
										$players->sendMessage('§a奇跡なぐらいのレア泥だ!  §r'.$itemname.'');
									}
									$players->getInventory()->addItem($item);
								}
							}
							if(isset($this->main->entity[$eid]["drop9"]) and isset($this->main->entity[$eid]["dropkakuritu9"])){
								$drop = $this->main->entity[$eid]["drop9"];
								$rarity = $this->main->RarityMark($this->main->citem[$drop]["rarity"]);
								$item = Item::get($this->main->citem[$drop]["id"], $this->main->citem[$drop]["meta"], 1)->setCustomName($this->main->citem[$drop]["cname"]."\n§6レア度§r: ".$rarity);
								if(isset($this->main->citem[$drop]["colorR"]) and isset($this->main->citem[$drop]["colorG"]) and isset($this->main->citem[$drop]["colorB"])){
									$color = new Color($this->main->citem[$drop]["colorR"], $this->main->citem[$drop]["colorG"], $this->main->citem[$drop]["colorB"]);
									$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
									$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
								}
								if(isset($this->main->citem[$drop]["name"])){
									$item->setNamedTagEntry(new StringTag("name", $this->main->citem[$drop]["name"]));
								}
								if(isset($this->main->citem[$drop]["atk"])){
									$item->setNamedTagEntry(new IntTag("status", $this->main->citem[$drop]["atk"]));
								}
								if(isset($this->main->citem[$drop]["def"])){
									$item->setNamedTagEntry(new IntTag("def", $this->main->citem[$drop]["def"]));
								}
								if(isset($this->main->citem[$drop]["sell"])){
									$item->setNamedTagEntry(new IntTag("sell", $this->main->citem[$drop]["sell"]));
								}
								if(isset($this->main->citem[$drop]["special"])){
									$item->setNamedTagEntry(new StringTag("special", $this->main->citem[$drop]["special"]));
								}
								if(isset($this->main->citem[$drop]["sinka"])){
									$item->setNamedTagEntry(new StringTag("sinka", $this->main->citem[$drop]["sinka"]));
								}
								if(isset($this->main->citem[$drop]["sozainame1"])){
									$item->setNamedTagEntry(new StringTag("sozainame1", $this->main->citem[$drop]["sozainame1"]));
								}
								if(isset($this->main->citem[$drop]["sozai1"])){
									$item->setNamedTagEntry(new StringTag("sozai1", $this->main->citem[$drop]["sozai1"]));
								}
								if(isset($this->main->citem[$drop]["kosuu1"])){
									$item->setNamedTagEntry(new StringTag("kosuu1", $this->main->citem[$drop]["kosuu1"]));
								}
								if(isset($this->main->citem[$drop]["job"])){
									$item->setNamedTagEntry(new StringTag("job", $this->main->citem[$drop]["job"]));				
								}
								if(isset($this->main->citem[$drop]["mp"])){
									$item->setNamedTagEntry(new IntTag("mp", $this->main->citem[$drop]["mp"]));				
								}
								if(isset($this->main->citem[$drop]["type"])){
									$item->setNamedTagEntry(new StringTag("type", $this->main->citem[$drop]["type"]));								
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["petname"])){
									$item->setNamedTagEntry(new StringTag("petname", $this->main->citem[$drop]["petname"]));
								}
								if(isset($this->main->citem[$drop]["petatk"])){
									$item->setNamedTagEntry(new IntTag("petatk", $this->main->citem[$drop]["petatk"]));
								}
								if(isset($this->main->citem[$drop]["petspeed"])){
									$item->setNamedTagEntry(new IntTag("petspeed", $this->main->citem[$drop]["petspeed"]));
								}
								if(isset($this->main->citem[$drop]["petrange"])){
									$item->setNamedTagEntry(new IntTag("petrange", $this->main->citem[$drop]["petrange"]));
								}
								if(isset($this->main->citem[$drop]["petatkspeed"])){
									$item->setNamedTagEntry(new IntTag("petatkspeed", $this->main->citem[$drop]["petatkspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbtype"])){
									$item->setNamedTagEntry(new IntTag("petbtype", $this->main->citem[$drop]["petbtype"]));
								}
								if(isset($this->main->citem[$drop]["petbspeed"])){
									$item->setNamedTagEntry(new IntTag("petbspeed", $this->main->citem[$drop]["petbspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbsize"])){
									$item->setNamedTagEntry(new IntTag("petbsize", $this->main->citem[$drop]["petbsize"]));
								}
								if(isset($this->main->citem[$drop]["petskinid"]) and isset($this->main->citem[$drop]["petskindata"]) and isset($this->main->citem[$drop]["petcapedata"]) and isset($this->main->citem[$drop]["petgeometryname"]) and isset($this->main->citem[$drop]["petgeometrydata"])){
									$item->setNamedTagEntry(new StringTag("petskinid", $this->main->citem[$drop]["petskinid"]));
									$item->setNamedTagEntry(new StringTag("petskindata", $this->main->citem[$drop]["petskindata"]));
									$item->setNamedTagEntry(new StringTag("petcapedata", $this->main->citem[$drop]["petcapedata"]));
									$item->setNamedTagEntry(new StringTag("petgeometryname", $this->main->citem[$drop]["petgeometryname"]));
									$item->setNamedTagEntry(new StringTag("petgeometrydata", $this->main->citem[$drop]["petgeometrydata"]));
								}
								$item->setNamedTagEntry(new IntTag("kyouka", 0));
								$dropp = $this->main->entity[$eid]["dropkakuritu9"];
								if($dropp - $minusrand < 1){
									$rand = mt_rand(1, 1);
								}else{
									$rand = mt_rand(1, $dropp - $minusrand);
								}
								if($rand === 1){
									$itemname = str_replace(PHP_EOL, '', $this->main->citem[$drop]["cname"]);
									if($dropp <= 25){
										$players->sendMessage('§7普通のドロップがおちた §r'.$itemname.'');
									}elseif($dropp > 25 and $dropp <= 1000){
										$players->sendMessage('§dレア泥がおちた! §r'.$itemname.'');
									}elseif($dropp > 1000){
										$players->sendMessage('§b物凄くレアドロがおちた!  §r'.$itemname.'');
									}elseif($dropp > 10000){
										$players->sendMessage('§a奇跡なぐらいのレア泥だ!  §r'.$itemname.'');
									}
									$players->getInventory()->addItem($item);
								}
							}
							if(isset($this->main->entity[$eid]["drop10"]) and isset($this->main->entity[$eid]["dropkakuritu10"])){
								$drop = $this->main->entity[$eid]["drop10"];
								$rarity = $this->main->RarityMark($this->main->citem[$drop]["rarity"]);
								$item = Item::get($this->main->citem[$drop]["id"], $this->main->citem[$drop]["meta"], 1)->setCustomName($this->main->citem[$drop]["cname"]."\n§6レア度§r: ".$rarity);
								if(isset($this->main->citem[$drop]["colorR"]) and isset($this->main->citem[$drop]["colorG"]) and isset($this->main->citem[$drop]["colorB"])){
									$color = new Color($this->main->citem[$drop]["colorR"], $this->main->citem[$drop]["colorG"], $this->main->citem[$drop]["colorB"]);
									$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
									$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
								}
								if(isset($this->main->citem[$drop]["name"])){
									$item->setNamedTagEntry(new StringTag("name", $this->main->citem[$drop]["name"]));
								}
								if(isset($this->main->citem[$drop]["atk"])){
									$item->setNamedTagEntry(new IntTag("status", $this->main->citem[$drop]["atk"]));
								}
								if(isset($this->main->citem[$drop]["def"])){
									$item->setNamedTagEntry(new IntTag("def", $this->main->citem[$drop]["def"]));
								}
								if(isset($this->main->citem[$drop]["sell"])){
									$item->setNamedTagEntry(new IntTag("sell", $this->main->citem[$drop]["sell"]));
								}
								if(isset($this->main->citem[$drop]["special"])){
									$item->setNamedTagEntry(new StringTag("special", $this->main->citem[$drop]["special"]));
								}
								if(isset($this->main->citem[$drop]["sinka"])){
									$item->setNamedTagEntry(new StringTag("sinka", $this->main->citem[$drop]["sinka"]));
								}
								if(isset($this->main->citem[$drop]["sozainame1"])){
									$item->setNamedTagEntry(new StringTag("sozainame1", $this->main->citem[$drop]["sozainame1"]));
								}
								if(isset($this->main->citem[$drop]["sozai1"])){
									$item->setNamedTagEntry(new StringTag("sozai1", $this->main->citem[$drop]["sozai1"]));
								}
								if(isset($this->main->citem[$drop]["kosuu1"])){
									$item->setNamedTagEntry(new StringTag("kosuu1", $this->main->citem[$drop]["kosuu1"]));
								}
								if(isset($this->main->citem[$drop]["job"])){
									$item->setNamedTagEntry(new StringTag("job", $this->main->citem[$drop]["job"]));				
								}
								if(isset($this->main->citem[$drop]["mp"])){
									$item->setNamedTagEntry(new IntTag("mp", $this->main->citem[$drop]["mp"]));				
								}
								if(isset($this->main->citem[$drop]["type"])){
									$item->setNamedTagEntry(new StringTag("type", $this->main->citem[$drop]["type"]));								
								}
								if(isset($this->main->citem[$drop]["rarity"])){
									$item->setNamedTagEntry(new IntTag("rarity", $this->main->citem[$drop]["rarity"]));				
								}
								if(isset($this->main->citem[$drop]["petname"])){
									$item->setNamedTagEntry(new StringTag("petname", $this->main->citem[$drop]["petname"]));
								}
								if(isset($this->main->citem[$drop]["petatk"])){
									$item->setNamedTagEntry(new IntTag("petatk", $this->main->citem[$drop]["petatk"]));
								}
								if(isset($this->main->citem[$drop]["petspeed"])){
									$item->setNamedTagEntry(new IntTag("petspeed", $this->main->citem[$drop]["petspeed"]));
								}
								if(isset($this->main->citem[$drop]["petrange"])){
									$item->setNamedTagEntry(new IntTag("petrange", $this->main->citem[$drop]["petrange"]));
								}
								if(isset($this->main->citem[$drop]["petatkspeed"])){
									$item->setNamedTagEntry(new IntTag("petatkspeed", $this->main->citem[$drop]["petatkspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbtype"])){
									$item->setNamedTagEntry(new IntTag("petbtype", $this->main->citem[$drop]["petbtype"]));
								}
								if(isset($this->main->citem[$drop]["petbspeed"])){
									$item->setNamedTagEntry(new IntTag("petbspeed", $this->main->citem[$drop]["petbspeed"]));
								}
								if(isset($this->main->citem[$drop]["petbsize"])){
									$item->setNamedTagEntry(new IntTag("petbsize", $this->main->citem[$drop]["petbsize"]));
								}
								if(isset($this->main->citem[$drop]["petskinid"]) and isset($this->main->citem[$drop]["petskindata"]) and isset($this->main->citem[$drop]["petcapedata"]) and isset($this->main->citem[$drop]["petgeometryname"]) and isset($this->main->citem[$drop]["petgeometrydata"])){
									$item->setNamedTagEntry(new StringTag("petskinid", $this->main->citem[$drop]["petskinid"]));
									$item->setNamedTagEntry(new StringTag("petskindata", $this->main->citem[$drop]["petskindata"]));
									$item->setNamedTagEntry(new StringTag("petcapedata", $this->main->citem[$drop]["petcapedata"]));
									$item->setNamedTagEntry(new StringTag("petgeometryname", $this->main->citem[$drop]["petgeometryname"]));
									$item->setNamedTagEntry(new StringTag("petgeometrydata", $this->main->citem[$drop]["petgeometrydata"]));
								}
								$item->setNamedTagEntry(new IntTag("kyouka", 0));
								$dropp = $this->main->entity[$eid]["dropkakuritu10"];
								if($dropp - $minusrand < 1){
									$rand = mt_rand(1, 1);
								}else{
									$rand = mt_rand(1, $dropp - $minusrand);
								}
								if($rand === 1){
									$itemname = str_replace(PHP_EOL, '', $this->main->citem[$drop]["cname"]);
									if($dropp <= 10){
										$players->sendMessage('§7普通のドロップがおちた §r'.$itemname.'');
									}elseif($dropp > 10 and $dropp <= 1000){
										$players->sendMessage('§dレア泥がおちた! §r'.$itemname.'');
									}elseif($dropp > 1000){
										$players->sendMessage('§b物凄くレアドロがおちた!  §r'.$itemname.'');
									}elseif($dropp > 10000){
										$players->sendMessage('§a奇跡なぐらいのレア泥だ!  §r'.$itemname.'');
									}
									$players->getInventory()->addItem($item);
								}
							}
						}
					unset($this->main->atkeid[$name][$eid]);
					}
				}
			}
			$name = $player->getName();
			$cname = $this->main->entity[$eid]["name"];
			$uuid = $this->main->entity[$eid]["uuid"];
			$this->main->$cname = $this->main->$cname - 1;
			if($mob === "§a§lGreenMonster"){
				if(isset($this->main->questplugin->q[$name]["greenmonster.kill"])){
					if($this->main->questplugin->q[$name]["greenmonster.kill"]["finish"] === 0){
						$this->main->questplugin->q[$name]["greenmonster.kill"]["now"] = $this->main->questplugin->q[$name]["greenmonster.kill"]["now"] + 1;
						$now = $this->main->questplugin->q[$name]["greenmonster.kill"]["now"];
						if($now < 10){
							$players->sendMessage("§e>>Quest<<\n§aGreenMonster討伐\n".$now." / 10");
						}elseif($now === 10){
							$players->sendMessage("§eGreenMonster討伐クエストをクリアしました");
							$this->main->questplugin->q[$name]["greenmonster.kill"]["finish"] = 1;
						}
						foreach($this->main->questplugin->q[$name] as $t){
							$this->main->questplugin->quest[$name]->set($t["quest"], $t);
						}
						$this->main->questplugin->quest[$name]->save();
					}
				}
			}
			$pk = new EntityEventPacket();
			$pk->entityRuntimeId = $eid;
			$pk->event = 3;
			$pk2 = new RemoveEntityPacket();
			$pk2->entityUniqueId = $eid;
			$this->main->getServer()->removePlayerListData($uuid, $this->main->getServer()->getOnlinePlayers());
			unset($this->main->entity[$eid]);
			unset($this->main->eid[$eid]);
			foreach($this->main->getServer()->getOnlinePlayers() as $players){
				$players->dataPacket($pk);
				$players->dataPacket($pk2);
			}
		}
	}
}
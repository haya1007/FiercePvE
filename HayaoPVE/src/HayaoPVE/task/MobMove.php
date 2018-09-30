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

class MobMove extends Task{

	function __construct(PluginBase $owner){
		$this->owner = $owner;
	}

	function onRun(int $currentTick){
		if(isset($this->owner->eid)){
			foreach($this->owner->eid as $eid){
				if(isset($this->owner->entity[$eid])){
					if($this->owner->entity[$eid]["move"] === 1){
						$x = $this->owner->entity[$eid]["x"];
						$y = $this->owner->entity[$eid]["y"];
						$z = $this->owner->entity[$eid]["z"];
						$yaw = $this->owner->entity[$eid]["yaw"];
						$pitch = $this->owner->entity[$eid]["pitch"];
						$target = $this->owner->entity[$eid]["target"];
						if(isset($this->entity[$eid]["plusY"])){
							$plusY = $this->entity[$eid]["plusY"];
							if($y < $plusY + 1){
								$this->owner->MobClose($eid);
							}
						}else{
							if($y < 0){
								$this->owner->MobClose($eid);
							}							
						}
						$targetplayer = $this->owner->getServer()->getPlayer($target);
						$this->owner->BossBar();
							if($targetplayer instanceof Player){
								if(isset($this->owner->entity[$eid]["searchdistance"])){
									$searchdistance = $this->owner->entity[$eid]["searchdistance"];
									$pos = new Vector3($x,$y + 1.62,$z);
									if($targetplayer->distanceSquared($pos) <= pow($searchdistance,2)){
									//if($targetplayer->distance($pos) <= $searchdistance){
										$speed = $this->owner->entity[$eid]["speed"];
										$this->owner->entity[$eid]["move"] = 1;
										$px = $targetplayer->x;
										$py = $targetplayer->y;
										$pz = $targetplayer->z;
										$epx = $px - $x;
										$epy = $py - $y;
										$epz = $pz - $z;
										$playerdistance = $this->owner->entity[$eid]["playerdistance"];
										if($targetplayer->distanceSquared($pos) <= pow($playerdistance,2)){
										//if($targetplayer->distance($pos) <= $playerdistance){
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
										$level = $targetplayer->getLevel();
										$blockid = $level->getBlock(new Vector3($x, $y, $z))->getID();
										$blockid2 = $level->getBlock(new Vector3($x, $yy, $z))->getID();
										$blockid3 = $level->getBlock(new Vector3($x, $y + 1, $z))->getID();
										if($blockid !== 0 and $blockid !== 6 and $blockid !== 8 and $blockid !== 9 and $blockid !== 10 and $blockid !== 11 and $blockid !== 27 and $blockid !== 28 and $blockid !== 30 and $blockid !== 31 and $blockid !== 32 and $blockid !== 37 and $blockid !== 38 and $blockid !== 39 and $blockid !== 40 and $blockid !== 50 and $blockid !== 51 and $blockid !== 55 and $blockid !== 59 and $blockid !== 63 and $blockid !== 68 and $blockid !== 70 and $blockid !== 72 and $blockid !== 75 and $blockid !== 76 and $blockid !== 78 and $blockid !== 83 and $blockid !== 90 and $blockid !== 104 and $blockid !== 105 and $blockid !== 106 and $blockid !== 115 and $blockid !== 119 and $blockid !== 126 and $blockid !== 132 and $blockid !== 141 and $blockid !== 142 and $blockid !== 147 and $blockid !== 148 and $blockid !== 171 and $blockid !== 175  and $blockid !== 199 and $blockid !== 244){
											$y++;
										}elseif($blockid2 === 0 or $blockid2 === 6 or $blockid2 === 8 or $blockid2 === 9 or $blockid2 === 10 or $blockid2 === 11 or $blockid2 === 27 or $blockid2 === 28 or $blockid2 === 30 or $blockid2 === 31 or $blockid2 === 32 or $blockid2 === 37 or $blockid2 === 38 or $blockid2 === 39 or $blockid2 === 40 or $blockid2 === 50 or $blockid2 === 51 or $blockid2 === 55 or $blockid2 === 59 or $blockid2 === 63 or $blockid2 === 68 or $blockid2 === 70 or $blockid2 === 72 or $blockid2 === 75 or $blockid2 === 76 or $blockid2 === 78 or $blockid2 === 83 or $blockid2 === 90 or $blockid2 === 104 or $blockid2 === 105 or $blockid2 === 106 or $blockid2 === 115 or $blockid2 === 119 or $blockid2 === 126 or $blockid2 === 132 or $blockid2 === 141 or $blockid2 === 142 or $blockid2 === 147 or $blockid2 === 148 or $blockid2 === 171 or $blockid2 === 175 or $blockid2 === 199 or $blockid2 === 244){
											$y--;
										}elseif($blockid3 !== 0 and $blockid3 !== 8 and $blockid3 !== 9 and $blockid3 !== 10 and $blockid3 !== 11){
											$y++;
										}
										/*if($this->owner->entity[$eid]["name"] === "MagicSkeleton"){
											$rand = mt_rand(1,30);
											if($rand === 1){
												$nbt = new CompoundTag("", [
												      "Pos" => new ListTag("Pos", [
												       new DoubleTag("", $this->owner->entity[$eid]["x"]+1),
												       new DoubleTag("", $this->owner->entity[$eid]["y"] +1),
												       new DoubleTag("", $this->owner->entity[$eid]["z"])
												        ]),
												         "Motion" => new ListTag("Motion", [
												          new DoubleTag("", -sin($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI)*5),
												          new DoubleTag("", -sin($pitch / 180 * M_PI)*5),
												          new DoubleTag("", cos($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI)*5)
												       ]),
												        "Rotation" => new ListTag("Rotation",[
												         new DoubleTag("", \lcg_value() * 360),
												         new DoubleTag("", 0)]),]);
												#$chunk = $this->owner->getServer()->getLevelByName("world")->getChunk($this->owner->entity[$eid]["x"] >> 4, $this->owner->entity[$eid]["z"] >> 4);
												$entity = Entity::createEntity("PrimedTNT", $this->owner->getServer()->getDefaultLevel(), $nbt);
												$entity->spawnToAll();
											}
										}*/
										$pk = new MovePlayerPacket();
										$pk->entityRuntimeId = $eid;
										if(isset($this->owner->entity[$eid]["plusY"])){
											$plusy = $this->owner->entity[$eid]["plusY"];
											$pk->position = new Vector3($x, $y + 1.62 + $plusy, $z);
										}else{
											$pk->position = new Vector3($x, $y + 1.62, $z);
										}
										$pk->pitch = $pitch;
										$pk->yaw = $yaw;
										$pk->headYaw = $yaw;
										foreach($this->owner->getServer()->getOnlinePlayers() as $players){
											$players->dataPacket($pk);
										}
									}
										$this->owner->entity[$eid]["x"] = $x;
										$this->owner->entity[$eid]["y"] = $y;
										$this->owner->entity[$eid]["z"] = $z;
										$this->owner->entity[$eid]["yaw"] = $yaw;
										$this->owner->entity[$eid]["pitch"] = $pitch;
										if(isset($this->owner->entity[$eid]["target"])){
											$target = $this->owner->entity[$eid]["target"];
											//$this->owner->getScheduler()->scheduleDelayedTask(new MobMove($this->owner, $eid, $x, $y, $z, $yaw, $pitch, $target), $this->owner->roopspeed);//ループ
											$this->owner->entity[$eid]["atktime"] = $this->owner->entity[$eid]["atktime"] + $this->owner->roopspeed;
											$atktime = $this->owner->entity[$eid]["atktime"];
											$reatk = $this->owner->entity[$eid]["reatk"];
											if($atktime >= $reatk){
												$atk = $this->owner->entity[$eid]["atk"];
												if(isset($this->owner->entity[$eid]["btype"]) and isset($this->owner->entity[$eid]["bspeed"]) and isset($this->owner->entity[$eid]["bsize"])){
													$batk = $this->owner->entity[$eid]["batk"];
													$btype = $this->owner->entity[$eid]["btype"];
													$bspeed = $this->owner->entity[$eid]["bspeed"];
													$bsize = $this->owner->entity[$eid]["bsize"];
													$this->owner->Shoot($targetplayer, $btype, $batk, $bspeed, $bsize, $x, $y + 1.1, $z, $yaw, $pitch, $level);
												}else{
													$this->owner->mob_MobATK->MobATK($eid, $target, $atk);
													$this->owner->entity[$eid]["atktime"] = 0;
												}
											}
										}else{
											$this->owner->entity[$eid]["move"] = 0;
										}
										foreach($this->owner->getServer()->getOnlinePlayers() as $players){
											$mobname = $this->owner->entity[$eid]["name"];
											$x = $this->owner->entity[$eid]["x"];
											$y = $this->owner->entity[$eid]["y"];
											$z = $this->owner->entity[$eid]["z"];
											$yaw = $this->owner->entity[$eid]["yaw"];
											$pitch = $this->owner->entity[$eid]["pitch"];
											$itemid = $this->owner->entity[$eid]["item"];
											$type = $this->owner->entity[$eid]["type"];
											if($type === "無"){
												$color = "§f";
											}elseif($type === "火"){
												$color = "§c";
											}elseif($type === "水"){
												$color = "§b";
											}elseif($type === "木"){
												$color = "§a";
											}elseif($type === "光"){
												$color = "§e";
											}elseif($type === "闇"){
												$color = "§6";
											}
											$level = $this->owner->entity[$eid]["level"];
											$mobname = "§l§5Lv. ".$level."§r§l ".$mobname." §r§l(".$color."".$type."§r§l)";
											if($this->owner->entity[$eid]["name"] === "§a§lGreenMonster"){
												if($this->owner->entity[$eid]["hp"] < 500){
													if($this->owner->entity[$eid]["change"] === 0){
														$pk = new AddPlayerPacket();
														$pk->entityRuntimeId = $eid;
														$pk->uuid = UUID::fromRandom();
														$pk->username = $mobname;
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
																Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $mobname],
															  	Entity::DATA_FLAG_NO_AI => [Entity::DATA_TYPE_BYTE, 1],
															  	Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
																Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 2],//大きさ
															  	];
													 	$skin = $this->owner->entity[$eid]["skin"];
													 	$xbox = $this->owner->entity[$eid]["xbox"];
													 	$this->owner->getServer()->updatePlayerListData($pk->uuid, $pk->entityRuntimeId, $mobname, $skin, $xbox, $this->owner->getServer()->getOnlinePlayers());
														$players->dataPacket($pk);
														$pk2 = new MobEquipmentPacket();
														$pk2->entityRuntimeId = $eid;
														$pk2->item = Item::get(intval($itemid),0,1);
														$pk2->inventorySlot = 0;
														$pk2->hotbarSlot = 0;
														$players->dataPacket($pk2);//Item
														$this->owner->entity[$eid]["change"] = 1;
													}
												}
											}
									}
								}else{
									$this->owner->entity[$eid]["move"] = 0;
									unset($this->owner->entity[$eid]["target"]);
								}
							}else{
								$this->owner->entity[$eid]["move"] = 0;
								unset($this->owner->entity[$eid]["target"]);
							}
						}
				}
			}
		}
	}
}
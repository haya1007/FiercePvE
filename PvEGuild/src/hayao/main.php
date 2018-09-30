<?php

namespace hayao;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandExecutor;
use pocketmine\scheduler\PluginTask;
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
use HayaoPVE\PvEItem;

class main extends PluginBase implements Listener{
	public function onEnable(){
		$this->server = $this->getServer();
		$this->server->getPluginManager()->registerEvents($this, $this);
		if(!file_exists($this->getDataFolder())){mkdir($this->getDataFolder(), 0744, true);}
		$this->g = new Config($this->getDataFolder().'Guild.json', Config::JSON, array());
		$this->b = new Config($this->getDataFolder()."asure.yml",Config::YAML);
		$this->guild = $this->g->getAll();
		if($this->getServer()->getPluginManager()->getPlugin("HayaoPVE") != null){
			$this->pve = $this->getServer()->getPluginManager()->getPlugin("HayaoPVE");
		}
    }
	public function onDisable(){
		foreach($this->guild as $t){
			$this->g->set($t["leader"], $t);
		}
		$this->g->save();
	}

	public function Join(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		if($this->checkGuild($player->getName())){
			foreach($this->getServer()->getOnlinePlayers() as $players){
				$player->showPlayer($players);
				$players->showPlayer($player);
			}
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if($label === "guild"){
			if(!isset($args[0])){
				$sender->sendMessage("§c/guild help");
			}elseif($args[0] === "help"){
				$data = [
					'type'    => 'form',
					'title'   => '§e★§b----------Guild----------§e★',
					'content' => "§dGuild機能の一覧§r\n\n/guild help / このFormを開きます\n/guild make [Guild名] / Guildの作成(1万M必要)\n/guild add [Player名] / プレイヤーに加入申請を出します(ﾘｰﾀﾞｰのみ)\n/guild kick [Player名] / メンバーをGuildからキックします(ﾘｰﾀﾞｰのみ)\n/guild chat / GuildChat\n/guild quiet /guildから抜ける\n/guild tp [Player名] /ギルドメンバーにワープ\n/guild room /ギルドﾙｰﾑにワープ",
					'buttons' => [
			 		    ['text' => "閉じる"]
					]
				];
				$this->createWindow($sender, $data, 12345);	
			}elseif($args[0] === "make"){
				$this->guild = $this->g->getAll();
				if(!isset($args[1])){
					$sender->sendMessage("§c/guild make [Guild名]");
				}else{
					$guild = $args[1];
					if($this->pve->config[$sender->getName()]->get("guild") === ""){
						if(!isset($this->guild[$guild])){
							$money = $this->getMoney($sender->getName());
							if($money >= 10000){
								$this->removeMoney($sender->getName(), 10000);
								$this->makeGuild($guild, $sender);
								$sender->sendMessage("§aギルドを作成しました!!!");
								$this->pve->config[$sender->getName()]->set("guild", $guild);
								$this->pve->config[$sender->getName()]->save();
								$level = $this->pve->getLevel($sender->getName());
								$sender->setNameTag('§b[§r'.$guild.'§r§b] §eLv.'.$level.' §l§f'.$sender->getName().'§r');	
								$sender->setDisplayName('§b[§r'.$guild.'§r§b] §eLv.'.$level.' §l§f'.$sender->getName().'§r');	
								$sender->save();
							}else{
								$sender->sendMessage("§cお金が足りません");
							}
						}else{
							$sender->sendMessage("§cそのギルドは既に存在します");
						}
					}else{
						$sender->sendMessage("§c既にギルドに入っています");
					}
				}
			}elseif($args[0] === "add"){
				$this->guild = $this->g->getAll();
				if(!isset($args[1])){
					$sender->sendMessage("§c/guild add [Player名]");
				}else{
					if($this->pve->config[$sender->getName()]->get("guild") !== ""){
						$name1 = $this->guild[$sender->getName()]["leader"];
						if($name1 === $sender->getName()){
							$max = 6;
							if(count($this->guild[$sender->getName()]["members"]) == $max){
									$sender->sendMessage("§cギルドが最大人数まで埋まっています");
							}else{
								$player = $this->getServer()->getPlayer($args[1]);
								if(isset($player)){
									if($player->isOnline()){
										if($this->pve->config[$player->getName()]->get("guild") === ""){
											$data = [
												'type'    => 'modal',
												'title'   => "Guild申請",
												'content' => "".$sender->getName()."さんからギルドの加入申請が届きました\n承諾しますか??\n",
												'button1' => "承諾する",
												'button2' => "やめる",
											];
											$this->createWindow($player, $data, 12346);
											$sender->sendMessage("§a".$player->getName()."さんに加入申請を送りました");
											$this->member[$player->getName()] = $sender->getName();
										}else{
											$sender->sendMessage("§cそのプレイヤーは既にギルドに属しています");
										}
									}else{
										$sender->sendMessage("§cそのプレイヤーはオフラインです");
									}
								}else{
									$sender->sendMessage("§cその名前のプレイヤーは存在しません");
								}
							}
						}else{
							$sender->sendMessage("§cあなたはﾘｰﾀﾞｰじゃありません");
						}
					}else{
						$sender->sendMessage("§cあなたはguildに属していません");
					}
				}
			}elseif($args[0] === "kick"){
				if(!isset($args[1])){
					$sender->sendMessage("§c/guild kick [Player名]");
				}else{
					$this->guild = $this->g->getAll();
					if($this->pve->config[$sender->getName()]->get("guild") !== ""){
						$name1 = $this->guild[$sender->getName()]["leader"];
						if($name1 === $sender->getName()){
							$player = $this->getServer()->getPlayer($args[1]);
							if(isset($player)){
								if($this->pve->config[$player->getName()]->get("guild") === $this->pve->config[$sender->getName()]->get("guild")){
									if(!$player->getName() === $sender->getName()){
										$sender->sendMessage("§c自分自身をkickできません");
									}else{
										$sender->sendMessage("§a".$player->getName()."をkickしました");
										$player->sendMessage("§cギルドからキックされました");
										$level = $this->pve->getLevel($player->getName());
										$player->setNameTag('§eLv.'.$level.' §l§f'.$player->getName().'§r');	
										$player->setDisplayName('§eLv.'.$level.' §l§f'.$player->getName().'§r');	
										$this->pve->config[$player->getName()]->set("guild", "");
										$this->pve->config[$player->getName()]->save();
										while( ($index = array_search( $player->getName(), $this->guild[$sender->getName()]["members"], true )) !== false ) {
											unset($this->guild[$sender->getName()]["members"][$index]);
										}
										if(!$this->pve->config[$player->getName()]->get("chat") === "flase"){
											$this->pve->config[$player->getName()]->set("chat", "false");
											$this->pve->config[$player->getName()]->save();						
										}
										foreach($this->guild as $t){
											$this->g->set($t["leader"], $t);
										}
										$this->g->save();
										$player->save();
									}
								}else{
									$sender->sendMessage("§cそのプレイヤーはあなたのギルドに属していません");
								}
							}else{
								$sender->sendMessage("§cその名前のプレイヤーは存在しません");
							}
						}else{
							$sender->sendMessage("§cあなたはﾘｰﾀﾞｰじゃありません");
						}
					}else{
						$sender->sendMessage("§cあなたはguildに属していません");
					}
				}
			}elseif($args[0] === "chat"){
				if($this->checkGuild($sender->getName()) === "true"){
					if($this->pve->config[$sender->getName()]->get("chat") === "false"){
						$this->pve->config[$sender->getName()]->set("chat", "true");
						$this->pve->config[$sender->getName()]->save();
						$sender->sendMessage("§aGuildChatをonにしました");
					}else{
						$this->pve->config[$sender->getName()]->set("chat", "false");
						$this->pve->config[$sender->getName()]->save();	
						$sender->sendMessage("§aGuildChatをoffにしました");				
					}
				}else{
					$sender->sendMessage("§cあなたはguildに属していません");
				}
			}elseif($args[0] === "quiet"){
				$name = $sender->getName();
				if($this->checkGuild($name) === "true"){
					$guild = $this->pve->config[$name]->get("guild");
					foreach($this->guild as $t){
						if($t["GuildName"] === $guild){
							if($t["leader"] !== $name){
								foreach($this->getServer()->getOnlinePlayers() as $players){
									if($this->pve->config[$players->getName()]->get("guild") === $guild){
										if($players->getName() !== $name){
											$players->sendMessage("§c".$name."さんがギルドから抜けました");
										}
									}
								}
								$sender->sendMessage("§cギルドから抜けました");
								$level = $this->pve->getLevel($name);
								$sender->setNameTag('§eLv.'.$level.' §l§f'.$name.'§r');	
								$sender->setDisplayName('§eLv.'.$level.' §l§f'.$name.'§r');	
								$this->pve->config[$name]->set("guild", "");
								$this->pve->config[$name]->save();
								while( ($index = array_search( $name, $t["members"], true )) !== false ) {
									unset($t["members"][$index]);
								}
								if(!$this->pve->config[$sender->getName()]->get("chat") === "flase"){
									$this->pve->config[$sender->getName()]->set("chat", "false");
									$this->pve->config[$sender->getName()]->save();						
								}
								$this->g->set($t["leader"], $t);
								$this->g->save();
								$sender->save();
							}else{
								$sender->sendMessage("§cﾘｰﾀﾞｰは退出できません");
							}
						}	
					}
				}else{
					$sender->sendMessage("§cあなたはguildに属していません");
				}
			}/*elseif($args[0] === "lost"){
				$name = $sender->getName();
				if($this->checkGuild($name) === "true"){
					$guild = $this->pve->config[$name]->get("guild");
					foreach($this->guild as $t){
						if($t["GuildName"] === $guild){
							if($t["leader"] === $name){
									unset($this->guild[$sender->getName()]);
									foreach($this->getServer()->getOnlinePlayers() as $players){
										if($this->pve->config[$players->getName()]->get("guild") === $guild){
											$players->sendMessage("§c".$name."さんがギルドを解散しました");
											$level = $this->pve->getLevel($name);
											$players->setNameTag('§eLv.'.$level.' §l§f'.$players->getName().'§r');	
											$players->setDisplayName('§eLv.'.$level.' §l§f'.$players->getName().'§r');	
											$players->save();
											$this->pve->config[$players->getName()]->save();
										}
									}
									foreach($t["members"] as $member){
										$member = $this->getServer()->getPlayer($member);
										$level = $this->pve->getLevel($member->getName());
										$member->setNameTag('§eLv.'.$level.' §l§f'.$member->getName().'§r');	
										$member->setDisplayName('§eLv.'.$level.' §l§f'.$member->getName().'§r');	
										$member->save();
										$this->pve->config[$member->getName()]->set("guild", "");
										$this->pve->config[$member->getName()]->save();
										if($member->isOnline()){
											$member->sendMessage("§c".$name."さんがギルドを解散しました");
										}
									}
							}else{
								$sender->sendMessage("§cあなたはﾘｰﾀﾞｰじゃありません");
							}
						}	
					}
				}else{
					$sender->sendMessage("§cあなたはguildに属していません");
				}
			}*/elseif($args[0] === "tp"){
				/*if(!isset($args[1])){
					$sender->sendMessage("§c/guild tp [Player名]");
				}else{
					$this->guild = $this->g->getAll();
					if($this->pve->config[$sender->getName()]->get("guild") !== ""){
						$player = $this->getServer()->getPlayer($args[1]);
						if(isset($player)){
							if($player->isOnline()){
								if($this->pve->config[$player->getName()]->get("guild") === $this->pve->config[$sender->getName()]->get("guild")){
									if(!$player->getName() === $sender->getName()){
										$sender->sendMessage("§c自分自身にワープできません");
									}else{
										$sender->sendMessage("§a".$player->getName()."にワープしました");
										$player->sendMessage("§a".$sender->getName()."がワープしてきました");
										$x = $player->getX();
										$y = $player->getY();
										$z = $player->getZ();
										$pos = new Vector3($x, $y, $z);
										$sender->teleport($pos);
									}
								}else{
									$sender->sendMessage("§cそのプレイヤーは同じギルドじゃないか、ギルドに属していません");
								}
							}else{
								$sender->sendMessage("§cそのプレイヤーはオフラインです");
							}
						}else{
							$sender->sendMessage("§cその名前のプレイヤーは存在しません");
						}
					}else{
						$sender->sendMessage("§cあなたはguildに属していません");
					}
				}*/
				$sender->sendMessage("§c廃止なう");
			}elseif($args[0] === "room"){
				$name = $sender->getName();
				if($this->checkGuild($name) === "true"){
					$pos = new Vector3(121.7, 16.5, -70.7);
					$sender->teleport($pos);
					$sender->sendMessage("§aギルドﾙｰﾑにワープしました");
					foreach($this->getServer()->getOnlinePlayers() as $players){
						if($this->pve->config[$name]->get("guild") !== $this->pve->config[$players->getName()]->get("guild")){
							$sender->hidePlayer($players);
							$players->hidePlayer($sender);
						}
					}
				}else{
					$sender->sendMessage("§cあなたはguildに属していません");
				}
			}
		}
		return true;
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
				if($id === 12346){
					if($data == "true\n"){
						$name2 = $this->member[$name];
						$this->guild[$name2]["members"][] = $name;
						foreach($this->guild as $t){
							$this->g->set($t["leader"], $t);
						}
						$this->g->save();
						$p->sendMessage("§aギルドに加入しました");
						$guild = $this->guild[$name2]["GuildName"];
						$this->pve->config[$name]->set("guild", $guild);
						$this->pve->config[$name]->save();
						$player = $this->getServer()->getPlayer($name2);
						$player->sendMessage("§a".$name."さんが加入しました");
						unset($this->member[$name]);
						$level = $this->pve->getLevel($name);
						$p->setNameTag('§b[§r'.$guild.'§r§b] §eLv.'.$level.' §l§f'.$name.'§r');	
						$p->setDisplayName('§b[§r'.$guild.'§r§b] §eLv.'.$level.' §l§f'.$name.'§r');	
						$p->save();
					}else{
						$p->sendMessage("§c申請を拒否しました");
						$player = $this->getServer()->getPlayer($this->member[$name]);
						$player->sendMessage("§c承諾を拒否されました");
					}
				}
			}
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
			$data = $this->b->getAll();
			$money = $data[$var]["money"];
			$rank = $data[$var]["rank"];
			$this->addMoney($name, $money);
			$pos = new Vector3(26.5, 6.5, 116.5);
			$player->teleport($pos);
			foreach($this->getServer()->getOnlinePlayers() as $players){
				$players->sendMessage("§a".$name."さんが§b".$rank."アスレチック§aをクリアしました!");
				$player->sendMessage("§a賞品として".$money."Mが渡されました");
			}
		}
		if($block->getX() === 119 and $block->getY() === 17 and $block->getZ() === -71 or $block->getX() === 119 and $block->getY() === 17 and $block->getZ() === -72){
			$event->setCancelled();
			$pos = new Vector3(26.5, 6.5, 116.5);
			$player->teleport($pos);
			$player->sendMessage("§aロビーにワープしました");
			foreach($this->getServer()->getOnlinePlayers() as $players){
				if($this->pve->config[$name]->get("guild") !== $this->pve->config[$players->getName()]){
					$player->showPlayer($players);
					$players->showPlayer($player);
				}
			}
		}
	}

    public function onSignChange(SignChangeEvent $event){
		$result = $event->getLine(0);
		if($result === "asure"){
			$player = $event->getPlayer();
			if(!$player->isOp()){
				$player->sendMessage("§c権限がありません");
				return;
            }
			$block = $event->getBlock();
            $money = $event->getLine(1);
            $rank = $event->getLine(2);
	       if(is_numeric($money)){
				$var = (Int)$event->getBlock()->getX().":".(Int)$event->getBlock()->getY().":".(Int)$event->getBlock()->getZ().":".$block->getLevel()->getFolderName();
	          	$this->b->set($var, [
					"x" => $block->getX(),
					"y" => $block->getY(),
					"z" => $block->getZ(),
					"level" => $block->getLevel()->getFolderName(),
					"money" => $money,
					"rank" => $rank,
				]);
	            $this->b->save();
				$player->sendMessage("§bアスレチックゴール看板を作りました!");
	                    $X = (Int)$event->getBlock()->getX();
	                    $Y = (Int)$event->getBlock()->getY();
	                    $Z = (Int)$event->getBlock()->getZ();
	                    $w = $block->getLevel();
	                    $y = $Y - 1;
				$event->setLine(0, "§a[アスレチック]"); // TAG
				$event->setLine(1, "§fゴールおめでとう!(Tapして)");
				$event->setLine(2, "§a難易度: ".$rank); 
				$event->setLine(3, "§a賞品: ".$money."M"); 
			}else{
			#	$player->sendMessage("§c正しく入力してください");
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
                $player->sendMessage("§cアスレチックゴール看板を解体しました");
            }else{
                $name = $player->getName();
                $event->setCancelled();
            }
        }
    }

	public function makeGuild($guild, $player){
		if(!isset($this->guild[$guild])){
			$this->guild[$guild] = [
				"GuildName" => $guild,
				"leader" => $player->getName(),
				"members" => [
					"0" => $player->getName(),
				]
			];
			foreach($this->guild as $t){
				$this->g->set($t["leader"], $t);
			}
			$this->g->save();
		}
	}

	public function checkGuild($name){
		if($this->pve->config[$name]->get("guild") === ""){
			return false;
		}else{
			return true;
		}
	}

	public function addMoney($name, $plus){
		$this->pve->addMoney($name, $plus);
	}

	public function removeMoney($name, $remove){
		$this->pve->removeMoney($name, $remove);
	}

	public function getMoney($name){
		return $this->pve->getMoney($name);
	}

	public function createWindow(Player $player, $data, int $id){
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
		$player->dataPacket($pk);
	}
}
<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220622200733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_sessions (id UUID NOT NULL, game_id UUID NOT NULL, winner_id UUID DEFAULT NULL, last_player_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finished BOOLEAN NOT NULL, board JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_31246235E48FD905 ON game_sessions (game_id)');
        $this->addSql('CREATE INDEX IDX_312462355DFCD4B8 ON game_sessions (winner_id)');
        $this->addSql('CREATE INDEX IDX_312462358E292AD9 ON game_sessions (last_player_id)');
        $this->addSql('COMMENT ON COLUMN game_sessions.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game_sessions.game_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game_sessions.winner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game_sessions.last_player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game_sessions.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN game_sessions.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE game_session_player (game_session_id UUID NOT NULL, player_id UUID NOT NULL, PRIMARY KEY(game_session_id, player_id))');
        $this->addSql('CREATE INDEX IDX_2DB82C708FE32B32 ON game_session_player (game_session_id)');
        $this->addSql('CREATE INDEX IDX_2DB82C7099E6F5DF ON game_session_player (player_id)');
        $this->addSql('COMMENT ON COLUMN game_session_player.game_session_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game_session_player.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE games (id UUID NOT NULL, players SMALLINT NOT NULL, board_width SMALLINT NOT NULL, board_height SMALLINT NOT NULL, winning_sequence SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN games.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE players (id UUID NOT NULL, username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN players.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE game_sessions ADD CONSTRAINT FK_31246235E48FD905 FOREIGN KEY (game_id) REFERENCES games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_sessions ADD CONSTRAINT FK_312462355DFCD4B8 FOREIGN KEY (winner_id) REFERENCES players (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_sessions ADD CONSTRAINT FK_312462358E292AD9 FOREIGN KEY (last_player_id) REFERENCES players (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_session_player ADD CONSTRAINT FK_2DB82C708FE32B32 FOREIGN KEY (game_session_id) REFERENCES game_sessions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_session_player ADD CONSTRAINT FK_2DB82C7099E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game_session_player DROP CONSTRAINT FK_2DB82C708FE32B32');
        $this->addSql('ALTER TABLE game_sessions DROP CONSTRAINT FK_31246235E48FD905');
        $this->addSql('ALTER TABLE game_sessions DROP CONSTRAINT FK_312462355DFCD4B8');
        $this->addSql('ALTER TABLE game_sessions DROP CONSTRAINT FK_312462358E292AD9');
        $this->addSql('ALTER TABLE game_session_player DROP CONSTRAINT FK_2DB82C7099E6F5DF');
        $this->addSql('DROP TABLE game_sessions');
        $this->addSql('DROP TABLE game_session_player');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

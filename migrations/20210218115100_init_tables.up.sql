CREATE TABLE IF NOT EXISTS `areas` (
    `id`         BINARY(16)   NOT NULL COMMENT '(DC2Type:ulid)',
    `title`      VARCHAR(255) NOT NULL,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `follows`    BINARY(16)            DEFAULT NULL COMMENT '(DC2Type:ulid)',
    CONSTRAINT `uqx__areas__follows` UNIQUE INDEX (`follows`),
    CONSTRAINT `fk__areas__follows` FOREIGN KEY (`follows`) REFERENCES `areas` (`id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_0900_ai_ci`
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tags` (
    `id`         BINARY(16)   NOT NULL COMMENT '(DC2Type:ulid)',
    `title`      VARCHAR(255) NOT NULL,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `uqx__tags__name` UNIQUE INDEX (`title`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_0900_ai_ci`
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `projects` (
    `id`         BINARY(16)   NOT NULL COMMENT '(DC2Type:ulid)',
    `area`       BINARY(16)   NOT NULL COMMENT '(DC2Type:ulid)',
    `title`      VARCHAR(255) NOT NULL,
    `notes`      TEXT                  DEFAULT NULL,
    `start_date` DATETIME              DEFAULT NULL,
    `deadline`   DATETIME              DEFAULT NULL,
    `completed`  DATETIME              DEFAULT NULL,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `follows`    BINARY(16)            DEFAULT NULL COMMENT '(DC2Type:ulid)',
    CONSTRAINT `uqx__projects__follows` UNIQUE INDEX (`follows`),
    CONSTRAINT `fk__projects__area`     FOREIGN KEY (`area`)    REFERENCES `areas` (`id`),
    CONSTRAINT `fk__projects__follows`  FOREIGN KEY (`follows`) REFERENCES `projects` (`id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_0900_ai_ci`
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `project_tags` (
    `project` BINARY(16) NOT NULL COMMENT '(DC2Type:ulid)',
    `tag`     BINARY(16) NOT NULL COMMENT '(DC2Type:ulid)',
    CONSTRAINT `fk__project_tags__project` FOREIGN KEY (`project`) REFERENCES `projects` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `fk__project_tags__tag`     FOREIGN KEY (`tag`)     REFERENCES `tags` (`id`)
        ON DELETE CASCADE,
    PRIMARY KEY (`project`, `tag`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_0900_ai_ci`
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `headings` (
    `id`          BINARY(16) NOT NULL COMMENT '(DC2Type:ulid)',
    `project`     BINARY(16) NOT NULL COMMENT '(DC2Type:ulid)',
    `title`       VARCHAR(255)   NOT NULL,
    `archived`    BOOLEAN    NOT NULL DEFAULT FALSE,
    `created_at`  DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `follows`     BINARY(16)          DEFAULT NULL COMMENT '(DC2Type:ulid)',
    CONSTRAINT `uqx__headings__follows` UNIQUE INDEX (`follows`),
    CONSTRAINT `fk__headings__project`  FOREIGN KEY (`project`) REFERENCES `projects` (`id`),
    CONSTRAINT `fk__headings__follows`  FOREIGN KEY (`follows`) REFERENCES `headings` (`id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_0900_ai_ci`
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tasks` (
    `id`         BINARY(16)   NOT NULL COMMENT '(DC2Type:ulid)',
    `project`    BINARY(16)   NOT NULL COMMENT '(DC2Type:ulid)',
    `heading`    BINARY(16)            DEFAULT NULL COMMENT '(DC2Type:ulid)',
    `title`      VARCHAR(255) NOT NULL,
    `notes`      TEXT                  DEFAULT NULL,
    `start_date` DATETIME              DEFAULT NULL,
    `deadline`   DATETIME              DEFAULT NULL,
    `completed`  DATETIME              DEFAULT NULL,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `follows`    BINARY(16)            DEFAULT NULL COMMENT '(DC2Type:ulid)',
    CONSTRAINT `uqx__tasks__follows` UNIQUE INDEX (`follows`),
    CONSTRAINT `fk__tasks__project`  FOREIGN KEY (`project`) REFERENCES `projects` (`id`),
    CONSTRAINT `fk__tasks__heading`  FOREIGN KEY (`heading`) REFERENCES `headings` (`id`),
    CONSTRAINT `fk__tasks__follows`  FOREIGN KEY (`follows`) REFERENCES `tasks` (`id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_0900_ai_ci`
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `task_tags` (
    `task` BINARY(16) NOT NULL COMMENT '(DC2Type:ulid)',
    `tag`  BINARY(16) NOT NULL COMMENT '(DC2Type:ulid)',
    CONSTRAINT `fk__task_tags__task` FOREIGN KEY (`task`) REFERENCES `tasks` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `fk__task_tags__tag`  FOREIGN KEY (`tag`)  REFERENCES `tags` (`id`)
        ON DELETE CASCADE,
    PRIMARY KEY (`task`, `tag`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_0900_ai_ci`
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `checklists` (
    `id`          BINARY(16) NOT NULL COMMENT '(DC2Type:ulid)',
    `task`        BINARY(16) NOT NULL COMMENT '(DC2Type:ulid)',
    `description` TEXT       NOT NULL,
    `completed`   DATETIME            DEFAULT NULL,
    `created_at`  DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `follows`     BINARY(16)          DEFAULT NULL COMMENT '(DC2Type:ulid)',
    CONSTRAINT `uqx__checklists__follows` UNIQUE INDEX (`follows`),
    CONSTRAINT `fk__checklists__task`  FOREIGN KEY (`task`) REFERENCES `tasks` (`id`),
    CONSTRAINT `fk__checklists__follows`  FOREIGN KEY (`follows`) REFERENCES `checklists` (`id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_0900_ai_ci`
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `areas` (
    `id` CHAR(26) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `follows` CHAR(26) DEFAULT NULL,
    CONSTRAINT `uqx__areas__follows` UNIQUE INDEX (`follows`),
    CONSTRAINT `fk__areas__follows` FOREIGN KEY (`follows`) REFERENCES `areas` (`id`),
    PRIMARY KEY (`id`)
)
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tags` (
    `id` CHAR(26) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    CONSTRAINT `uqx__tags__name` UNIQUE INDEX (`name`),
    PRIMARY KEY (`id`)
)
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `projects` (
    `id` CHAR(26) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `notes` TEXT DEFAULT NULL,
    `when` DATETIME DEFAULT NULL,
    `deadline` DATETIME DEFAULT NULL,
    `completed` DATETIME NOT NULL,
    `follows` CHAR(26) DEFAULT NULL,
    CONSTRAINT `uqx__projects__follows` UNIQUE INDEX (`follows`),
    CONSTRAINT `fk__projects__follows` FOREIGN KEY (`follows`) REFERENCES `projects` (`id`),
    PRIMARY KEY (`id`)
)
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `project_tags` (
    `project` CHAR(26) NOT NULL,
    `tag` CHAR(26) NOT NULL,
    CONSTRAINT `fk__project_tags__project` FOREIGN KEY (`project`) REFERENCES `projects` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `fk__project_tags__tag` FOREIGN KEY (`tag`) REFERENCES `tags` (`id`)
        ON DELETE CASCADE,
    PRIMARY KEY (`project`, `tag`)
)
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `headings` (
    `id` CHAR(26) NOT NULL,
    `project` CHAR(26) NOT NULL,
    `archived` BOOLEAN NOT NULL DEFAULT FALSE,
    `follows` CHAR(26) DEFAULT NULL,
    CONSTRAINT `uqx__headings__follows` UNIQUE INDEX (`follows`),
    CONSTRAINT `fk__headings__follows` FOREIGN KEY (`follows`) REFERENCES `headings` (`id`),
    PRIMARY KEY (`id`)
)
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tasks` (
    `id` CHAR(26) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `notes` TEXT DEFAULT NULL,
    `when` DATETIME DEFAULT NULL,
    `deadline` DATETIME DEFAULT NULL,
    `completed` DATETIME DEFAULT NULL,
    `follows` CHAR(26) DEFAULT NULL,
    CONSTRAINT `uqx__tasks__follows` UNIQUE INDEX (`follows`),
    CONSTRAINT `fk__tasks__follows` FOREIGN KEY (`follows`) REFERENCES `tasks` (`id`),
    PRIMARY KEY (`id`)
)
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `task_tags` (
    `task` CHAR(26) NOT NULL,
    `tag` CHAR(26) NOT NULL,
    CONSTRAINT `fk__task_tags__project` FOREIGN KEY (`task`) REFERENCES `tasks` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `fk__task_tags__tag` FOREIGN KEY (`tag`) REFERENCES `tags` (`id`)
        ON DELETE CASCADE,
    PRIMARY KEY (`task`, `tag`)
)
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `checklists` (
    `id` CHAR(26) NOT NULL,
    `description` TEXT NOT NULL,
    `completed` DATETIME DEFAULT NULL,
    `follows` CHAR(26) DEFAULT NULL,
    CONSTRAINT `uqx__checklists__follows` UNIQUE INDEX (`follows`),
    CONSTRAINT `fk__checklists__follows` FOREIGN KEY (`follows`) REFERENCES `checklists` (`id`),
    PRIMARY KEY (`id`)
)
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci
ENGINE = InnoDB;

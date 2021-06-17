create table pheature_toggles
(
    feature_id varchar(36)  not null
        primary key,
    name       varchar(140) not null,
    enabled    smallint   not null,
    strategies json         not null,
    created_at timestamp(0)     not null ,
    updated_at timestamp(0)     null
)
;

create index IDX_592B07D98B8E8428
    on pheature_toggles (created_at);

INSERT INTO public.pheature_toggles (feature_id, name, enabled, strategies, created_at, updated_at) VALUES ('feature_1', 'Feature 1', 0, '[]', '2021-06-17 23:08:34', null);
INSERT INTO public.pheature_toggles (feature_id, name, enabled, strategies, created_at, updated_at) VALUES ('feature_2', 'Feature 2', 1, '[]', '2021-06-17 23:08:54', null);
INSERT INTO public.pheature_toggles (feature_id, name, enabled, strategies, created_at, updated_at) VALUES ('feature_3', 'Feature 3', 0, '[]', '2021-06-17 23:08:34', null);
INSERT INTO public.pheature_toggles (feature_id, name, enabled, strategies, created_at, updated_at) VALUES ('feature_4', 'Feature 4', 1, '[]', '2021-06-17 23:08:54', null);
INSERT INTO public.pheature_toggles (feature_id, name, enabled, strategies, created_at, updated_at) VALUES ('feature_5', 'Feature 5', 0, '[]', '2021-06-17 23:08:34', null);
INSERT INTO public.pheature_toggles (feature_id, name, enabled, strategies, created_at, updated_at) VALUES ('feature_6', 'Feature 6', 1, '[]', '2021-06-17 23:08:54', null);
INSERT INTO public.pheature_toggles (feature_id, name, enabled, strategies, created_at, updated_at) VALUES ('feature_7', 'Feature 7', 0, '[]', '2021-06-17 23:08:34', null);
INSERT INTO public.pheature_toggles (feature_id, name, enabled, strategies, created_at, updated_at) VALUES ('feature_8', 'Feature 8', 1, '[]', '2021-06-17 23:08:54', null);
INSERT INTO public.pheature_toggles (feature_id, name, enabled, strategies, created_at, updated_at) VALUES ('feature_9', 'Feature 9', 0, '[]', '2021-06-17 23:08:34', null);
INSERT INTO public.pheature_toggles (feature_id, name, enabled, strategies, created_at, updated_at) VALUES ('feature_10', 'Feature 10', 1, '[]', '2021-06-17 23:08:54', null);

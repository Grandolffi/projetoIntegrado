PGDMP  6                    }            projetointegrado    17.2    17.2 4    �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            �           1262    25502    projetointegrado    DATABASE     �   CREATE DATABASE projetointegrado WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Portuguese_Brazil.1252';
     DROP DATABASE projetointegrado;
                     postgres    false            �            1259    25641    laudos    TABLE       CREATE TABLE public.laudos (
    id_laudo integer NOT NULL,
    paciente_id integer NOT NULL,
    solicitacao_id integer NOT NULL,
    responsavel_tecnico character varying(255),
    observacoes text,
    data_finalizacao timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
    DROP TABLE public.laudos;
       public         heap r       postgres    false            �            1259    25640    laudos_id_laudo_seq    SEQUENCE     �   CREATE SEQUENCE public.laudos_id_laudo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.laudos_id_laudo_seq;
       public               postgres    false    226            �           0    0    laudos_id_laudo_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.laudos_id_laudo_seq OWNED BY public.laudos.id_laudo;
          public               postgres    false    225            �            1259    25599 	   pacientes    TABLE     .  CREATE TABLE public.pacientes (
    id integer NOT NULL,
    nome character varying(255) NOT NULL,
    cpf character varying(255) NOT NULL,
    dtnasc date,
    email character varying(255),
    nomemae character varying(255),
    numcelular character varying(255),
    genero character varying(50)
);
    DROP TABLE public.pacientes;
       public         heap r       postgres    false            �            1259    25598    pacientes_id_seq    SEQUENCE     �   CREATE SEQUENCE public.pacientes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.pacientes_id_seq;
       public               postgres    false    220            �           0    0    pacientes_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.pacientes_id_seq OWNED BY public.pacientes.id;
          public               postgres    false    219            �            1259    25522    pessoa    TABLE     3  CREATE TABLE public.pessoa (
    id_pessoa integer NOT NULL,
    nome character varying(255) NOT NULL,
    cpf character varying(255) NOT NULL,
    dtnasc date,
    email character varying(255),
    nomemae character varying(255),
    numcelular character varying(255),
    genero character varying(255)
);
    DROP TABLE public.pessoa;
       public         heap r       postgres    false            �            1259    25521    pessoa_id_pessoa_seq    SEQUENCE     �   ALTER TABLE public.pessoa ALTER COLUMN id_pessoa ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.pessoa_id_pessoa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public               postgres    false    218            �            1259    25661    resultados_exames    TABLE     �  CREATE TABLE public.resultados_exames (
    id_exame integer NOT NULL,
    nome_exame character varying(255) NOT NULL,
    tipo_exame character varying(100) NOT NULL,
    valor_absoluto character varying(50),
    valor_referencia text,
    paciente_id_fk integer NOT NULL,
    data_hora_exame timestamp without time zone NOT NULL,
    data_cadastro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    laudo_id integer,
    paciente_registro character varying(255)
);
 %   DROP TABLE public.resultados_exames;
       public         heap r       postgres    false            �            1259    25660    resultados_exames_id_exame_seq    SEQUENCE     �   CREATE SEQUENCE public.resultados_exames_id_exame_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 5   DROP SEQUENCE public.resultados_exames_id_exame_seq;
       public               postgres    false    228            �           0    0    resultados_exames_id_exame_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE public.resultados_exames_id_exame_seq OWNED BY public.resultados_exames.id_exame;
          public               postgres    false    227            �            1259    25626    solicitacao_exames_itens    TABLE     D  CREATE TABLE public.solicitacao_exames_itens (
    id integer NOT NULL,
    solicitacao_id integer NOT NULL,
    nome_exame character varying(255) NOT NULL,
    tipo_exame_categoria character varying(100),
    valor_referencia_solicitacao text,
    status_item character varying(50) DEFAULT 'Pendente'::character varying
);
 ,   DROP TABLE public.solicitacao_exames_itens;
       public         heap r       postgres    false            �            1259    25625    solicitacao_exames_itens_id_seq    SEQUENCE     �   CREATE SEQUENCE public.solicitacao_exames_itens_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE public.solicitacao_exames_itens_id_seq;
       public               postgres    false    224                        0    0    solicitacao_exames_itens_id_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE public.solicitacao_exames_itens_id_seq OWNED BY public.solicitacao_exames_itens.id;
          public               postgres    false    223            �            1259    25610    solicitacoes    TABLE     }  CREATE TABLE public.solicitacoes (
    id_solicitacao integer NOT NULL,
    paciente_id integer NOT NULL,
    data_solicitacao timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    data_prevista_realizacao timestamp without time zone,
    solicitante_nome character varying(255),
    status character varying(50) DEFAULT 'Pendente'::character varying,
    observacoes text
);
     DROP TABLE public.solicitacoes;
       public         heap r       postgres    false            �            1259    25609    solicitacoes_id_solicitacao_seq    SEQUENCE     �   CREATE SEQUENCE public.solicitacoes_id_solicitacao_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE public.solicitacoes_id_solicitacao_seq;
       public               postgres    false    222                       0    0    solicitacoes_id_solicitacao_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE public.solicitacoes_id_solicitacao_seq OWNED BY public.solicitacoes.id_solicitacao;
          public               postgres    false    221            @           2604    25644    laudos id_laudo    DEFAULT     r   ALTER TABLE ONLY public.laudos ALTER COLUMN id_laudo SET DEFAULT nextval('public.laudos_id_laudo_seq'::regclass);
 >   ALTER TABLE public.laudos ALTER COLUMN id_laudo DROP DEFAULT;
       public               postgres    false    226    225    226            :           2604    25602    pacientes id    DEFAULT     l   ALTER TABLE ONLY public.pacientes ALTER COLUMN id SET DEFAULT nextval('public.pacientes_id_seq'::regclass);
 ;   ALTER TABLE public.pacientes ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    220    219    220            B           2604    25664    resultados_exames id_exame    DEFAULT     �   ALTER TABLE ONLY public.resultados_exames ALTER COLUMN id_exame SET DEFAULT nextval('public.resultados_exames_id_exame_seq'::regclass);
 I   ALTER TABLE public.resultados_exames ALTER COLUMN id_exame DROP DEFAULT;
       public               postgres    false    227    228    228            >           2604    25629    solicitacao_exames_itens id    DEFAULT     �   ALTER TABLE ONLY public.solicitacao_exames_itens ALTER COLUMN id SET DEFAULT nextval('public.solicitacao_exames_itens_id_seq'::regclass);
 J   ALTER TABLE public.solicitacao_exames_itens ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    224    223    224            ;           2604    25613    solicitacoes id_solicitacao    DEFAULT     �   ALTER TABLE ONLY public.solicitacoes ALTER COLUMN id_solicitacao SET DEFAULT nextval('public.solicitacoes_id_solicitacao_seq'::regclass);
 J   ALTER TABLE public.solicitacoes ALTER COLUMN id_solicitacao DROP DEFAULT;
       public               postgres    false    221    222    222            �          0    25641    laudos 
   TABLE DATA           {   COPY public.laudos (id_laudo, paciente_id, solicitacao_id, responsavel_tecnico, observacoes, data_finalizacao) FROM stdin;
    public               postgres    false    226   �D       �          0    25599 	   pacientes 
   TABLE DATA           ^   COPY public.pacientes (id, nome, cpf, dtnasc, email, nomemae, numcelular, genero) FROM stdin;
    public               postgres    false    220   &F       �          0    25522    pessoa 
   TABLE DATA           b   COPY public.pessoa (id_pessoa, nome, cpf, dtnasc, email, nomemae, numcelular, genero) FROM stdin;
    public               postgres    false    218   �G       �          0    25661    resultados_exames 
   TABLE DATA           �   COPY public.resultados_exames (id_exame, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_id_fk, data_hora_exame, data_cadastro, laudo_id, paciente_registro) FROM stdin;
    public               postgres    false    228   �H       �          0    25626    solicitacao_exames_itens 
   TABLE DATA           �   COPY public.solicitacao_exames_itens (id, solicitacao_id, nome_exame, tipo_exame_categoria, valor_referencia_solicitacao, status_item) FROM stdin;
    public               postgres    false    224   �J       �          0    25610    solicitacoes 
   TABLE DATA           �   COPY public.solicitacoes (id_solicitacao, paciente_id, data_solicitacao, data_prevista_realizacao, solicitante_nome, status, observacoes) FROM stdin;
    public               postgres    false    222   �K                  0    0    laudos_id_laudo_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.laudos_id_laudo_seq', 7, true);
          public               postgres    false    225                       0    0    pacientes_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.pacientes_id_seq', 7, true);
          public               postgres    false    219                       0    0    pessoa_id_pessoa_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.pessoa_id_pessoa_seq', 3, true);
          public               postgres    false    217                       0    0    resultados_exames_id_exame_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('public.resultados_exames_id_exame_seq', 12, true);
          public               postgres    false    227                       0    0    solicitacao_exames_itens_id_seq    SEQUENCE SET     N   SELECT pg_catalog.setval('public.solicitacao_exames_itens_id_seq', 16, true);
          public               postgres    false    223                       0    0    solicitacoes_id_solicitacao_seq    SEQUENCE SET     N   SELECT pg_catalog.setval('public.solicitacoes_id_solicitacao_seq', 10, true);
          public               postgres    false    221            Q           2606    25649    laudos laudos_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.laudos
    ADD CONSTRAINT laudos_pkey PRIMARY KEY (id_laudo);
 <   ALTER TABLE ONLY public.laudos DROP CONSTRAINT laudos_pkey;
       public                 postgres    false    226            I           2606    25608    pacientes pacientes_cpf_key 
   CONSTRAINT     U   ALTER TABLE ONLY public.pacientes
    ADD CONSTRAINT pacientes_cpf_key UNIQUE (cpf);
 E   ALTER TABLE ONLY public.pacientes DROP CONSTRAINT pacientes_cpf_key;
       public                 postgres    false    220            K           2606    25606    pacientes pacientes_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.pacientes
    ADD CONSTRAINT pacientes_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.pacientes DROP CONSTRAINT pacientes_pkey;
       public                 postgres    false    220            E           2606    25530    pessoa pessoa_cpf_key 
   CONSTRAINT     O   ALTER TABLE ONLY public.pessoa
    ADD CONSTRAINT pessoa_cpf_key UNIQUE (cpf);
 ?   ALTER TABLE ONLY public.pessoa DROP CONSTRAINT pessoa_cpf_key;
       public                 postgres    false    218            G           2606    25528    pessoa pessoa_pkey 
   CONSTRAINT     W   ALTER TABLE ONLY public.pessoa
    ADD CONSTRAINT pessoa_pkey PRIMARY KEY (id_pessoa);
 <   ALTER TABLE ONLY public.pessoa DROP CONSTRAINT pessoa_pkey;
       public                 postgres    false    218            S           2606    25669 (   resultados_exames resultados_exames_pkey 
   CONSTRAINT     l   ALTER TABLE ONLY public.resultados_exames
    ADD CONSTRAINT resultados_exames_pkey PRIMARY KEY (id_exame);
 R   ALTER TABLE ONLY public.resultados_exames DROP CONSTRAINT resultados_exames_pkey;
       public                 postgres    false    228            O           2606    25634 6   solicitacao_exames_itens solicitacao_exames_itens_pkey 
   CONSTRAINT     t   ALTER TABLE ONLY public.solicitacao_exames_itens
    ADD CONSTRAINT solicitacao_exames_itens_pkey PRIMARY KEY (id);
 `   ALTER TABLE ONLY public.solicitacao_exames_itens DROP CONSTRAINT solicitacao_exames_itens_pkey;
       public                 postgres    false    224            M           2606    25619    solicitacoes solicitacoes_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY public.solicitacoes
    ADD CONSTRAINT solicitacoes_pkey PRIMARY KEY (id_solicitacao);
 H   ALTER TABLE ONLY public.solicitacoes DROP CONSTRAINT solicitacoes_pkey;
       public                 postgres    false    222            X           2606    25675     resultados_exames fk_exame_laudo    FK CONSTRAINT     �   ALTER TABLE ONLY public.resultados_exames
    ADD CONSTRAINT fk_exame_laudo FOREIGN KEY (laudo_id) REFERENCES public.laudos(id_laudo);
 J   ALTER TABLE ONLY public.resultados_exames DROP CONSTRAINT fk_exame_laudo;
       public               postgres    false    4689    228    226            Y           2606    25670 #   resultados_exames fk_exame_paciente    FK CONSTRAINT     �   ALTER TABLE ONLY public.resultados_exames
    ADD CONSTRAINT fk_exame_paciente FOREIGN KEY (paciente_id_fk) REFERENCES public.pacientes(id);
 M   ALTER TABLE ONLY public.resultados_exames DROP CONSTRAINT fk_exame_paciente;
       public               postgres    false    4683    228    220            V           2606    25650    laudos fk_laudo_paciente    FK CONSTRAINT        ALTER TABLE ONLY public.laudos
    ADD CONSTRAINT fk_laudo_paciente FOREIGN KEY (paciente_id) REFERENCES public.pacientes(id);
 B   ALTER TABLE ONLY public.laudos DROP CONSTRAINT fk_laudo_paciente;
       public               postgres    false    226    4683    220            W           2606    25655    laudos fk_laudo_solicitacao    FK CONSTRAINT     �   ALTER TABLE ONLY public.laudos
    ADD CONSTRAINT fk_laudo_solicitacao FOREIGN KEY (solicitacao_id) REFERENCES public.solicitacoes(id_solicitacao);
 E   ALTER TABLE ONLY public.laudos DROP CONSTRAINT fk_laudo_solicitacao;
       public               postgres    false    226    4685    222            T           2606    25620 $   solicitacoes fk_solicitacao_paciente    FK CONSTRAINT     �   ALTER TABLE ONLY public.solicitacoes
    ADD CONSTRAINT fk_solicitacao_paciente FOREIGN KEY (paciente_id) REFERENCES public.pacientes(id);
 N   ALTER TABLE ONLY public.solicitacoes DROP CONSTRAINT fk_solicitacao_paciente;
       public               postgres    false    220    4683    222            U           2606    25635 E   solicitacao_exames_itens solicitacao_exames_itens_solicitacao_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.solicitacao_exames_itens
    ADD CONSTRAINT solicitacao_exames_itens_solicitacao_id_fkey FOREIGN KEY (solicitacao_id) REFERENCES public.solicitacoes(id_solicitacao) ON UPDATE CASCADE ON DELETE CASCADE;
 o   ALTER TABLE ONLY public.solicitacao_exames_itens DROP CONSTRAINT solicitacao_exames_itens_solicitacao_id_fkey;
       public               postgres    false    224    4685    222            �   W  x�e�MN�0F��)� P%-Miw�_!�P˒�e$�S&B\����E+�y����ƕ�,:�{�F�A���]H�O1��ؠ��ܰ����xXC5����b�r.p��'�\1&��V���z���F�'L��z���d��<�&���usl��G���p�5&=���dȭ�+�PT~�ȝK�6�J�@�=�>����m��;����1*(�7E�ﺺ�n�rv�ϼF�G�bEw��?�R� Qt������.'[_㳩��cw�Iy�аv��)�u��}�S��5q9;�ˀ	�*�%G�����I�$�][y+(�r���l�h
�t6ڡ�EQ�&d�f      �   i  x�U�MN�0�דS�����U%�P+��bcR#�[8iO���$-fV����f�νˍ�=i��*Y�8��К0.)�̠���0�^G�oRt���R��؟�������uy�C�q!Uݴ���N*	��2��֒�Xܛ���po&N�<Zw�еM���l涔$��"ʸǟo���.D�J�̳�1�k�哳c�$�o����v�)x_�UX�٤k�#Y]�Q��х��2#�j�-AZB��YT�(�b���&���,M�zB#�܆qBs�� <.n��´����Gv�ڊ���h���h�Φ��AS]�nEO�XE�CQ�?�1-inM|!����VEQ����h      �   �   x�M�AJ�0@��S�&I�6;� ��f�#iGү��Q���*طI/y3�s��Q��<v��a��,�IO��6�f]��s�If8������*N�{^���i�{ͼp{��"+�q�>t�5�41C����.*������N��4�e�x���/iO\�::�,�{pD��
N�u(\'���ٞs��7	J�6y¦i~ 7�V�      �   �  x����n�@���S�@f�]�V/)j�F$�l���o�V��k��F�*��>�_,K���m�|���Oa�*SXYI���[�ӵdcJSh	7�V���Z�0@�!>F>��e�Kı�z�y@)�����z>�:W��DU�J}m+x�����X�\B�����e��X��)��W�B�nV�r}�]k<`���x����Ol������ֈ�㐶e�ZIv��Zs/���:���E�d���nT!���hq�z�zRS�}��)��4Ή�p,�^�7��P�)K:�J�(FyHc���[D�E���謲�ǝ2�#MF�����b���i5E�b0_�|�ŉSg�q�]#
/��*'��cÑa����y���͑�&Y��K���V�N�_u�2P���qr��ٽ�=)b�Q�w.�����t�L��#�ƅM��v������E���,���*
�l��qZB0���*C�=�`�I���`0�I��8����#�-2`>��=z<      �   -  x���Mn�0�דS�P	�m����?�cc�i4��i�=�ހ�ա��������Ѡa��k1Ψ�ݻE�ݗ�l�&�'x����c2����N=�[������������\�`������4����U�z~5BM��H�����g�B����Z�76j?�R������B�F�	d�v1b�B�xj��c9�Æ�n�o��(� �w!m��HExqx��5L.����8Us��1#�^+;�g=���#UT�;�zy�\}S͏t��2�ek�\hU�!���<�C�N����?��v�O������      �   �  x����n�0�g�)�lP�d;�'�&��t�r�Y��4H�(�:���z�R�IV24��>��˘����DJWK!���J�Ĺv�q
�EX�����h5[=i�<��`�=.��#��|���m�3r
���Q�C>8C�"6���d�r�9nI!�Y>�g�(}���z�ꀆ�de������ 9���6�V����Ae:t�۟X� �E��KV^pG���z�p���ε���M�=c�~��y7�V{��$�j^����7/�KK��ѓ|����E��������|u꩟g��D�Tpʓ}[梟����k-�eN�QC���QV����́�)�5Z~5�"�ˬ��Hʥ(�.U<�����6�I��:���a���):ݽ�+)���L�ҫ�hd�I��_ߧ���q#0     